<?php

namespace App\Services;

use App\Models\Package;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\User;
use App\Services\Payments\PaymentGateway;
use App\Services\Payments\PaymentGatewayFactory;
use App\Services\Payments\PaymentResult;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Modèle « accès après l'essai » : 30 jours d'essai gratuit (users.end_trial_period),
 * puis un abonnement actif est nécessaire pour RECEVOIR les alertes push.
 */
class SubscriptionService
{
    public const TRIAL = 'trial';
    public const ACTIVE = 'active';
    public const EXPIRED = 'expired';

    /** Abonnement en cours ayant la date de fin la plus lointaine. */
    public function currentSubscription(User $user): ?Subscription
    {
        return Subscription::with('package')
            ->where('user_id', $user->id)
            ->where('end_date', '>', now())
            ->orderByDesc('end_date')
            ->first();
    }

    public function trialEndsAt(User $user): ?Carbon
    {
        return $user->end_trial_period
            ? Carbon::parse($user->end_trial_period)->endOfDay()
            : null;
    }

    /**
     * @return array{state: string, trial_ends_at: ?string, subscription_ends_at: ?string,
     *               plan: ?string, days_left: int}
     */
    public function status(User $user): array
    {
        $subscription = $this->currentSubscription($user);
        $trialEnd = $this->trialEndsAt($user);

        if ($subscription) {
            $state = self::ACTIVE;
            $endsAt = Carbon::parse($subscription->end_date);
        } elseif ($trialEnd && $trialEnd->isFuture()) {
            $state = self::TRIAL;
            $endsAt = $trialEnd;
        } else {
            $state = self::EXPIRED;
            $endsAt = null;
        }

        return [
            'state' => $state,
            'trial_ends_at' => $trialEnd?->toIso8601String(),
            'subscription_ends_at' => $subscription ? Carbon::parse($subscription->end_date)->toIso8601String() : null,
            'plan' => $subscription?->package?->name,
            // Jours restants arrondis au supérieur (« expire aujourd'hui » = 1)
            'days_left' => $endsAt ? (int) ceil(now()->diffInSeconds($endsAt, true) / 86400) : 0,
        ];
    }

    /**
     * L'utilisateur peut-il recevoir les alertes ? (essai en cours ou abonné)
     *
     * Tant qu'aucun moyen de paiement n'est disponible (pas encore
     * d'agrégateur en production), on ne bloque personne : ce serait couper
     * les alertes sans permettre de payer pour les rétablir.
     */
    public function isEntitled(User $user): bool
    {
        if (!$this->paymentsAvailable()) {
            return true;
        }
        return $this->status($user)['state'] !== self::EXPIRED;
    }

    public function paymentsAvailable(): bool
    {
        return app(PaymentGatewayFactory::class)->make() !== null;
    }

    /** Interroge l'agrégateur si le paiement est encore en attente. */
    public function refresh(SubscriptionPayment $payment, PaymentGateway $gateway): SubscriptionPayment
    {
        if ($payment->isPending()) {
            $this->applyResult($payment, $gateway->checkStatus($payment));
        }
        return $payment->fresh();
    }

    /**
     * Enregistre le résultat de l'agrégateur ; active l'abonnement en cas de
     * succès. Idempotent : un paiement déjà traité n'est jamais rejoué
     * (protection contre les appels concurrents app + webhook).
     */
    public function applyResult(SubscriptionPayment $payment, PaymentResult $result): void
    {
        DB::transaction(function () use ($payment, $result) {
            $locked = SubscriptionPayment::whereKey($payment->id)->lockForUpdate()->first();
            if (!$locked || !$locked->isPending()) {
                return;
            }

            $locked->provider_reference = $result->providerReference ?? $locked->provider_reference;

            if ($result->status === SubscriptionPayment::SUCCESS) {
                $subscription = $this->activate($locked->user, $locked->package);
                $locked->fill([
                    'status' => SubscriptionPayment::SUCCESS,
                    'paid_at' => now(),
                    'subscription_id' => $subscription->id,
                ]);
                Log::info('Abonnement activé', [
                    'user_id' => $locked->user_id,
                    'package' => $locked->package->name,
                    'reference' => $locked->reference,
                ]);
            } elseif ($result->status === SubscriptionPayment::FAILED) {
                $locked->fill([
                    'status' => SubscriptionPayment::FAILED,
                    'failure_reason' => $result->failureReason,
                ]);
            }

            $locked->save();
        });
    }

    /**
     * Crée la période d'abonnement. Cumulatif : un renouvellement avant
     * l'échéance démarre à la fin de l'abonnement en cours (aucun jour perdu).
     */
    private function activate(User $user, Package $package): Subscription
    {
        $current = $this->currentSubscription($user);
        $start = $current ? Carbon::parse($current->end_date) : now();

        return Subscription::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'start_date' => $start,
            'end_date' => $start->copy()->addMonths($package->duration),
        ]);
    }
}

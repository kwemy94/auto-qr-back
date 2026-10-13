<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\SubscriptionPayment;
use App\Services\Payments\PaymentGatewayFactory;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Abonnement premium payé par Mobile Money.
 *  GET  /api/subscription                      statut (essai / actif / expiré)
 *  GET  /api/subscription/plans                offres en vente
 *  POST /api/subscription/checkout             lance un paiement
 *  GET  /api/subscription/payments/{reference} suivi du paiement (interrogé par l'app)
 *  POST /api/payments/webhook/{provider}       notification de l'agrégateur (public)
 */
class SubscriptionApiController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptions,
        private PaymentGatewayFactory $gateways,
    ) {}

    public function status()
    {
        return response()->json([
            'success' => true,
            'subscription' => $this->subscriptions->status(Auth::user()),
            'payments_available' => $this->gateways->make() !== null,
        ]);
    }

    public function plans()
    {
        $plans = Package::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Package $p) => [
                'id' => $p->id,
                'name' => $p->name,
                'duration' => $p->duration,          // en mois
                'amount' => (int) $p->amount,
                'currency' => config('payments.currency'),
                'description' => $p->description,
            ]);

        return response()->json(['success' => true, 'plans' => $plans]);
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'package_id' => ['required', 'integer'],
            'operator' => ['required', 'in:' . implode(',', config('payments.operators'))],
            // Numéro camerounais : 9 chiffres commençant par 6 (indicatif 237 facultatif)
            'phone' => ['required', 'string', 'regex:/^(\+?237)?6\d{8}$/'],
        ]);

        $gateway = $this->gateways->make();
        if (!$gateway) {
            return response()->json([
                'success' => false,
                'message' => __('mobile.payments_unavailable'),
            ], 503);
        }

        $package = Package::where('is_active', true)->find($data['package_id']);
        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => __('mobile.plan_unavailable'),
                'errors' => ['package_id' => [__('mobile.plan_unavailable')]],
            ], 422);
        }

        $payment = SubscriptionPayment::create([
            'user_id' => Auth::id(),
            'package_id' => $package->id,
            'provider' => $gateway->name(),
            'amount' => $package->amount,
            'currency' => config('payments.currency'),
            'operator' => $data['operator'],
            'phone' => preg_replace('/^\+?237/', '', $data['phone']),
        ]);

        try {
            $this->subscriptions->applyResult($payment, $gateway->initiate($payment));
        } catch (\Throwable $e) {
            Log::error('Paiement : échec de l\'initialisation', [
                'reference' => $payment->reference,
                'error' => $e->getMessage(),
            ]);
            $payment->update(['status' => SubscriptionPayment::FAILED, 'failure_reason' => 'gateway_error']);
        }

        return response()->json([
            'success' => true,
            'payment' => $payment->fresh()->toApiArray(),
            'test_mode' => $gateway->name() === 'fake',
        ], 201);
    }

    public function payment(string $reference)
    {
        $payment = SubscriptionPayment::where('reference', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $gateway = $this->gateways->make($payment->provider);
        if ($gateway) {
            try {
                $payment = $this->subscriptions->refresh($payment, $gateway);
            } catch (\Throwable $e) {
                // Agrégateur injoignable : on renvoie le dernier statut connu
                Log::warning('Paiement : vérification impossible', [
                    'reference' => $reference,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'payment' => $payment->toApiArray(),
            'subscription' => $this->subscriptions->status(Auth::user()),
        ]);
    }

    public function webhook(Request $request, string $provider)
    {
        $gateway = $this->gateways->make($provider);
        if (!$gateway) {
            return response()->json(['success' => false], 404);
        }

        [$reference, $result] = $gateway->handleWebhook($request->all(), $request->headers->all());
        if (!$reference || !$result) {
            return response()->json(['success' => false], 400);
        }

        $payment = SubscriptionPayment::where('reference', $reference)
            ->where('provider', $provider)
            ->first();
        if ($payment) {
            $this->subscriptions->applyResult($payment, $result);
        }

        return response()->json(['success' => true]);
    }
}

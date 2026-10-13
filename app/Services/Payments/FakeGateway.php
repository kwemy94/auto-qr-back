<?php

namespace App\Services\Payments;

use App\Models\SubscriptionPayment;

/**
 * Paiement SIMULÉ, pour développer et tester sans agrégateur.
 *  - confirmé automatiquement après `payments.fake_confirm_after` secondes ;
 *  - un numéro se terminant par 000 simule un échec (solde insuffisant).
 *
 * Refusé en production sauf PAYMENT_ALLOW_FAKE=true (voir PaymentGatewayFactory).
 */
class FakeGateway implements PaymentGateway
{
    public function name(): string
    {
        return 'fake';
    }

    public function initiate(SubscriptionPayment $payment): PaymentResult
    {
        return PaymentResult::pending('FAKE-' . substr($payment->reference, 0, 8));
    }

    public function checkStatus(SubscriptionPayment $payment): PaymentResult
    {
        if (str_ends_with($payment->phone, '000')) {
            return PaymentResult::failed('insufficient_funds', $payment->provider_reference);
        }

        $delay = (int) config('payments.fake_confirm_after', 5);
        if ($payment->created_at->diffInSeconds(now(), true) >= $delay) {
            return PaymentResult::success($payment->provider_reference);
        }

        return PaymentResult::pending($payment->provider_reference);
    }

    public function handleWebhook(array $payload, array $headers): array
    {
        // Pas de webhook pour le paiement simulé
        return [null, null];
    }
}

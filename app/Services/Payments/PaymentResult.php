<?php

namespace App\Services\Payments;

use App\Models\SubscriptionPayment;

/** Réponse normalisée d'un agrégateur. */
final class PaymentResult
{
    public function __construct(
        public readonly string $status,              // SubscriptionPayment::PENDING|SUCCESS|FAILED
        public readonly ?string $providerReference = null,
        public readonly ?string $failureReason = null,
    ) {}

    public static function pending(?string $providerReference = null): self
    {
        return new self(SubscriptionPayment::PENDING, $providerReference);
    }

    public static function success(?string $providerReference = null): self
    {
        return new self(SubscriptionPayment::SUCCESS, $providerReference);
    }

    public static function failed(string $reason, ?string $providerReference = null): self
    {
        return new self(SubscriptionPayment::FAILED, $providerReference, $reason);
    }
}

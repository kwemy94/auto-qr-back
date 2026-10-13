<?php

namespace App\Services\Payments;

/**
 * Fournit l'agrégateur configuré (config/payments.php → driver),
 * ou null si le paiement n'est pas disponible.
 */
class PaymentGatewayFactory
{
    /** Agrégateurs disponibles : ajouter ici les vraies implémentations. */
    private const DRIVERS = [
        'fake' => FakeGateway::class,
        // 'notchpay' => NotchPayGateway::class,
    ];

    public function make(?string $driver = null): ?PaymentGateway
    {
        $driver ??= config('payments.driver');
        $class = self::DRIVERS[$driver] ?? null;

        if (!$class) {
            return null;
        }

        // Sécurité : pas d'abonnement gratuit en production
        if ($driver === 'fake' && app()->isProduction() && !config('payments.allow_fake_in_production')) {
            return null;
        }

        return app($class);
    }
}

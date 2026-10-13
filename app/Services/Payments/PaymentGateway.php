<?php

namespace App\Services\Payments;

use App\Models\SubscriptionPayment;

/**
 * Contrat d'un agrégateur Mobile Money (NotchPay, Campay, CinetPay…).
 *
 * Flux : initiate() demande le paiement (le client valide sur son téléphone),
 * puis le statut est obtenu par checkStatus() (interrogé par l'app) et/ou par
 * handleWebhook() (appelé par l'agrégateur).
 */
interface PaymentGateway
{
    /** Identifiant enregistré dans subscription_payments.provider */
    public function name(): string;

    /**
     * Lance la demande de paiement auprès de l'agrégateur.
     * Doit renseigner provider_reference si l'agrégateur en renvoie une.
     *
     * @return PaymentResult  statut PENDING en général (validation sur le téléphone)
     */
    public function initiate(SubscriptionPayment $payment): PaymentResult;

    /** Interroge l'agrégateur sur l'état actuel du paiement. */
    public function checkStatus(SubscriptionPayment $payment): PaymentResult;

    /**
     * Notification serveur-à-serveur de l'agrégateur.
     * Doit VÉRIFIER l'authenticité (signature / secret) avant de faire confiance.
     *
     * @return array{0: ?string, 1: ?PaymentResult}  [notre référence, résultat] ou [null, null] si invalide
     */
    public function handleWebhook(array $payload, array $headers): array;
}

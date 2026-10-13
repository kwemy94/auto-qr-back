<?php

return [

    /*
    | Agrégateur Mobile Money utilisé pour les abonnements.
    | "fake" : paiement simulé (développement / tests), confirmé
    | automatiquement après quelques secondes.
    | Un vrai agrégateur (notchpay, campay, cinetpay…) s'ajoute en écrivant une
    | classe qui implémente App\Services\Payments\PaymentGateway, puis en
    | l'enregistrant dans App\Services\Payments\PaymentGatewayFactory.
    */
    'driver' => env('PAYMENT_DRIVER', 'fake'),

    /*
    | Sécurité : le paiement simulé est REFUSÉ en production (sinon tout le
    | monde pourrait s'abonner gratuitement). Ne mettre à true que pour une
    | démo contrôlée.
    */
    'allow_fake_in_production' => env('PAYMENT_ALLOW_FAKE', false),

    // Paiement simulé : délai avant confirmation automatique (secondes)
    'fake_confirm_after' => env('PAYMENT_FAKE_DELAY', 5),

    'currency' => 'XAF',

    // Opérateurs Mobile Money acceptés
    'operators' => ['mtn', 'orange'],
];

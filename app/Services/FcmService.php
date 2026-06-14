<?php

// ============================================================
// app/Services/FcmService.php
// Service d'envoi de notifications push via Firebase FCM v1
// ============================================================

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google\Auth\Credentials\ServiceAccountCredentials;

class FcmService
{
    // Messages prédéfinis disponibles côté signalant
    // La clé est stockée en base, le texte affiché vient d'ici
    public const MESSAGES = [
        'blocking'  => '🚗 Votre véhicule bloque le passage. Merci de le déplacer.',
        'urgent'    => '🚨 Urgence ! Votre véhicule bloque complètement l\'accès.',
        'lights'    => '💡 Vos phares sont allumés. Pensez à les éteindre.',
        'window'    => '🪟 Une vitre de votre véhicule est ouverte.',
        'tires'     => '🔴 Un pneu de votre véhicule semble dégonflé.',
    ];

    private string $projectId;
    private string $credentialsPath;

    public function __construct()
    {
        $this->projectId     = config('services.fcm.project_id');
        $this->credentialsPath = config('services.fcm.credentials');
    }

    /**
     * Envoie une notification push au propriétaire du véhicule.
     *
     * @param  string $fcmToken   Token FCM du téléphone propriétaire
     * @param  string $messageKey Clé du message prédéfini
     * @return bool
     */
    public function send(string $fcmToken, string $messageKey): bool
    {
        if (!array_key_exists($messageKey, self::MESSAGES)) {
            Log::warning('FCM: clé de message invalide', ['key' => $messageKey]);
            return false;
        }

        $body = self::MESSAGES[$messageKey];

        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withToken($accessToken)
                ->post("https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send", [
                    'message' => [
                        'token' => $fcmToken,
                        'notification' => [
                            'title' => 'QR Notify 🔔',
                            'body'  => $body,
                        ],
                        'android' => [
                            'priority' => 'high',
                            'notification' => [
                                'sound'        => 'default',
                                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            ],
                        ],
                        'apns' => [
                            'payload' => [
                                'aps' => [
                                    'sound' => 'default',
                                    'badge' => 1,
                                ],
                            ],
                        ],
                        'data' => [
                            'message_key' => $messageKey,
                            'timestamp'   => now()->toIso8601String(),
                        ],
                    ],
                ]);

            if ($response->successful()) {
                Log::info('FCM: notification envoyée', ['message_key' => $messageKey]);
                return true;
            }

            Log::error('FCM: échec envoi', [
                'status'   => $response->status(),
                'response' => $response->json(),
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('FCM: exception', ['message' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Obtient un access token OAuth2 via le compte de service Google.
     * Nécessite : composer require google/auth
     */
    private function getAccessToken(): string
    {
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        $credentials = new ServiceAccountCredentials($scopes, $this->credentialsPath);
        $token = $credentials->fetchAuthToken();

        return $token['access_token'];
    }
}

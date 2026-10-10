<?php

// ============================================================
// app/Services/FcmService.php
// ============================================================

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google\Auth\Credentials\ServiceAccountCredentials;

class FcmService
{
    public const MESSAGES = [
        'blocking' => '🚗 Votre véhicule bloque le passage. Merci de le déplacer.',
        'urgent' => '🚨 Urgence ! Votre véhicule bloque complètement l\'accès.',
        'lights' => '💡 Vos phares sont allumés. Pensez à les éteindre.',
        'window' => '🪟 Une vitre de votre véhicule est ouverte.',
        'tires' => '🔴 Un pneu de votre véhicule semble dégonflé.',
    ];

    private string $projectId;
    private string $credentialsPath;

    public function __construct()
    {
        $this->projectId = config('services.fcm.project_id');

        // ── Résoudre le chemin absolu ─────────────────────────────────────
        // ServiceAccountCredentials exige un chemin absolu.
        // base_path() convertit un chemin relatif depuis la racine Laravel.
        $rawPath = config('services.fcm.credentials');

        $this->credentialsPath = str_starts_with($rawPath, '/')
            ? $rawPath
            : base_path($rawPath);
    }

    /**
     * Notification de signalement envoyée au propriétaire du véhicule.
     *
     * @param int|null $notificationId  id du QrNotification : permet à l'app
     *                                  d'ouvrir directement son détail au clic.
     */
    public function send(string $fcmToken, string $messageKey, ?int $notificationId = null): bool
    {
        if (!array_key_exists($messageKey, self::MESSAGES)) {
            Log::warning('FCM: clé de message invalide', ['key' => $messageKey]);
            return false;
        }

        return $this->dispatch($fcmToken, 'QR Notify 🔔', self::MESSAGES[$messageKey], [
            'message_key' => $messageKey,
            'notification_id' => $notificationId !== null ? (string) $notificationId : null,
        ]);
    }

    /**
     * Notification de test que l'utilisateur s'envoie depuis son profil,
     * pour vérifier que son téléphone reçoit bien les alertes.
     * N'est pas enregistrée comme signalement.
     */
    public function sendTest(string $fcmToken): bool
    {
        return $this->dispatch(
            $fcmToken,
            __('mobile.test_notification_title'),
            __('mobile.test_notification_body'),
            ['message_key' => 'test'],
        );
    }

    /**
     * Envoi effectif via l'API HTTP v1 de FCM.
     * Les valeurs nulles de $data sont ignorées.
     */
    private function dispatch(string $fcmToken, string $title, string $body, array $data): bool
    {
        // ── Vérifier que le fichier credentials existe ────────────────────
        if (!file_exists($this->credentialsPath)) {
            Log::error('FCM: fichier credentials introuvable', [
                'path' => $this->credentialsPath,
                'hint' => 'Vérifiez FCM_CREDENTIALS dans .env et placez le fichier JSON Firebase à cet emplacement.',
            ]);
            return false;
        }

        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withToken($accessToken)
                ->post("https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send", [
                    'message' => [
                        'token' => $fcmToken,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'android' => [
                            'priority' => 'high',
                            'notification' => [
                                'sound' => 'default',
                                // Icône monochrome et couleur de la marque, déclarées dans l'app
                                // (plugins/withNotificationIcon.js)
                                'icon' => 'ic_notification',
                                'color' => '#C9962A',
                                // Pas de click_action : Android ouvre alors l'app au clic.
                                // (FLUTTER_NOTIFICATION_CLICK ne correspondait à aucune activité.)
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
                        // Les valeurs de "data" doivent toutes être des chaînes (FCM v1)
                        'data' => array_filter(
                            $data + ['timestamp' => now()->toIso8601String()],
                            fn ($v) => $v !== null,
                        ),
                    ],
                ]);

            if ($response->successful()) {
                Log::info('FCM: notification envoyée', ['message_key' => $data['message_key'] ?? null]);
                return true;
            }

            // Token FCM invalide (app désinstallée) → logger pour nettoyage
            if (
                $response->status() === 404 ||
                str_contains((string) $response->body(), 'UNREGISTERED')
            ) {
                Log::warning('FCM: token invalide (UNREGISTERED)', [
                    'fcm_token' => substr($fcmToken, 0, 20),
                ]);
            }

            Log::error('FCM: échec envoi', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('FCM: exception', ['message' => $e->getMessage()]);
            return false;
        }
    }

    private function getAccessToken(): string
    {
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
        $credentials = new ServiceAccountCredentials($scopes, $this->credentialsPath);
        $token = $credentials->fetchAuthToken();

        if (empty($token['access_token'])) {
            throw new \RuntimeException('FCM: impossible d\'obtenir un access token Google.');
        }

        return $token['access_token'];
    }
}

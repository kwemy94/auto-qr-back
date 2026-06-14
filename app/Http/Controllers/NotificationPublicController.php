<?php

// ============================================================
// app/Http/Controllers/NotificationPublicController.php
// Page publique — accessible sans compte ni app
// ============================================================

namespace App\Http\Controllers;

use App\Models\QrNotification;
use App\Models\SignalLog;
use App\Models\Vehicle;
use App\Repositories\UserRepository;
use App\Services\FcmService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationPublicController extends Controller
{
    private $userRepository;
    public function __construct(
        private FcmService $fcm,
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    // ─────────────────────────────────────────────────────────
    // GET /n/{token}
    // Affiche la page de signalement au scanneur
    // ─────────────────────────────────────────────────────────
    public function show(string $token)
    {
        try {
            $user = $this->userRepository->findByToken($token);

            if (empty($user->fcm_token)) {
                return response()->view('errors.404', [
                    'message' => 'Ce propriétaire ne peut pas encore recevoir de notifications. Réessayez plus tard.',
                ], 404);
            }

            return view('notify.show', [
                'token' => $token,
                'messages' => FcmService::MESSAGES,
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->view('errors.404', [
                'message' => 'QR code invalide ou désactivé.',
            ], 404);

        } catch (\Exception $e) {
            Log::error('QR Notify - Erreur show()', [
                'token' => $token,
                'error' => $e->getMessage(),
            ]);

            return response()->view('errors.500', [
                'message' => 'Une erreur est survenue. Veuillez réessayer.',
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────
    // POST /n/{token}/send
    // Envoie la notification au propriétaire
    // ─────────────────────────────────────────────────────────
    public function send(Request $request, string $token)
    {
        $request->validate([
            'message_key' => ['required', 'string', 'in:' . implode(',', array_keys(FcmService::MESSAGES))],
        ]);

        $user = $this->userRepository->findByToken($token);

        // Anti-abus : max 3 signalements par 10 min par IP + user
        $ipHash = hash('sha256', $request->ip() . config('app.key'));
        $recentCount = QrNotification::where('user_id', $user->id)
            ->where('ip_hash', $ipHash)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count();

        if ($recentCount >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà signalé ce véhicule récemment. Merci de patienter.',
            ], 429);
        }

        // Envoi FCM
        Log::info('QR Notify - Nouveau signalement', [
            'user_id' => $user->id,
            'message_key' => $request->message_key,
        ]);
        $sent = false;
        if ($user->fcm_token) {
            $sent = $this->fcm->send($user->fcm_token, $request->message_key);
            Log::info('QR Notify - Notification envoyée', [
                'user_id' => $user->id,
                'message_key' => $request->message_key,
                'fcm_sent' => $sent,
            ]);
        }

        // ── Enregistrement en base ────────────────────────────────
        QrNotification::create([
            'user_id' => $user->id,
            'message_key' => $request->message_key,
            'message_text' => FcmService::MESSAGES[$request->message_key],
            'ip_hash' => $ipHash,
            'is_read' => false,
        ]);

        Log::info('QR Notify - Signalement enregistré', [
            'user_id' => $user->id,
            'message_key' => $request->message_key,
            'sent' => $sent,
        ]);

        return response()->json([
            'success' => true,
            'sent' => $sent,
            'message' => $sent
                ? 'Le propriétaire a été notifié avec succès.'
                : 'Signalement enregistré. Le propriétaire sera informé.',
        ]);
    }
}

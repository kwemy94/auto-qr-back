<?php

// ============================================================
// app/Http/Controllers/NotificationPublicController.php
// Page publique — accessible sans compte ni app
// ============================================================

namespace App\Http\Controllers;

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
        Log::info('QR Notify - Signalement reçu', [
            'token' => $token,
            'message_key' => $request->message_key,
            'ip' => $request->ip(),
        ]);
        // dd($request->all(), $token);
        $request->validate([
            'message_key' => ['required', 'string', 'in:' . implode(',', array_keys(FcmService::MESSAGES))],
        ]);

        $user = $this->userRepository->findByToken($token);

        // Anti-abus : max 3 signalements toutes les 10 minutes par IP + véhicule
        $ipHash = hash('sha256', $request->ip() . config('app.key'));
        $recentCount = SignalLog::where('user_id', $user->id)
            ->where('ip_hash', $ipHash)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count();

        if ($recentCount >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà signalé ce véhicule récemment. Merci de patienter.',
            ], 429);
        }

        // Envoi de la notification si le propriétaire a un token FCM
        $sent = false;
        if ($user->fcm_token) {
            $sent = $this->fcm->send($user->fcm_token, $request->message_key);
        }

        Log::info('QR Notify - Signalement reçu', [
            'user_id' => $user->id,
            'message_key' => $request->message_key,
            'ip_hash' => $ipHash,
            'sent' => $sent,
        ]);
        # Log du signalement (IP hashée — jamais en clair)
        SignalLog::create([
            'user_id' => $user->id,
            'message_key' => $request->message_key,
            'ip_hash' => $ipHash,
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

<?php

// ============================================================
// app/Http/Controllers/NotificationController.php
// Routes authentifiées — espace propriétaire
// ============================================================

namespace App\Http\Controllers;

use App\Models\QrNotification;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // ─────────────────────────────────────────────────────────
    // GET /api/notifications
    // Liste des notifications du user connecté (desc)
    // ─────────────────────────────────────────────────────────
    public function index()
    {
        $notifications = QrNotification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($n) => $n->toOwnerArray());

        $unread_count = QrNotification::where('user_id', Auth::id())
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unread_count,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // GET /api/notifications/{id}
    // Détail d'une notification + marquer comme lue
    // ─────────────────────────────────────────────────────────
    public function show(int $id)
    {
        $notification = QrNotification::where('user_id', Auth::id())
            ->findOrFail($id);

        // Marquer comme lue automatiquement à la consultation
        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return response()->json([
            'notification' => $notification->toOwnerArray(),
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // POST /api/notifications/{id}/respond  { response }
    // Le propriétaire répond au signaleur (« J'arrive », « Dans 5 min »…).
    // Peut être modifiée (ex. : « J'arrive » puis « C'est réglé »).
    // ─────────────────────────────────────────────────────────
    public function respond(Request $request, int $id)
    {
        $request->validate([
            'response' => ['required', 'string', 'in:' . implode(',', array_keys(QrNotification::RESPONSES))],
        ]);

        $notification = QrNotification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->update([
            'response_key' => $request->response,
            'responded_at' => now(),
            'is_read' => true,
            'read_at' => $notification->read_at ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'notification' => $notification->toOwnerArray(),
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // PUT /api/notifications/read-all
    // Marquer toutes les notifications comme lues
    // ─────────────────────────────────────────────────────────
    public function readAll()
    {
        QrNotification::where('user_id', Auth::id())
            ->unread()
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────────────────
    // POST /api/notifications/test
    // Envoie une notification de test au téléphone de l'utilisateur
    // (bouton « Tester » du profil). Non enregistrée comme signalement.
    // ─────────────────────────────────────────────────────────
    public function test(FcmService $fcm)
    {
        $user = Auth::user();

        if (empty($user->fcm_token)) {
            return response()->json([
                'success' => false,
                'message' => __('mobile.test_no_token'),
            ], 422);
        }

        $sent = $fcm->sendTest($user->fcm_token);

        return response()->json([
            'success' => $sent,
            'message' => $sent ? __('mobile.test_sent') : __('mobile.test_failed'),
        ], $sent ? 200 : 502);
    }
}

<?php

// ============================================================
// app/Http/Controllers/NotificationController.php
// Routes authentifiées — espace propriétaire
// ============================================================

namespace App\Http\Controllers;

use App\Models\QrNotification;
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
            ->map(fn($n) => [
                'id' => $n->id,
                'message_key' => $n->message_key,
                'message_text' => $n->message_text,
                'is_read' => $n->is_read,
                'read_at' => $n->read_at?->toIso8601String(),
                'created_at' => $n->created_at->toIso8601String(),
            ]);

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
            'notification' => [
                'id' => $notification->id,
                'message_key' => $notification->message_key,
                'message_text' => $notification->message_text,
                'is_read' => true,
                'read_at' => $notification->read_at?->toIso8601String(),
                'created_at' => $notification->created_at->toIso8601String(),
            ]
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
}

<?php

// ============================================================
// app/Models/QrNotification.php
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QrNotification extends Model
{
    /**
     * Réponses possibles du propriétaire (clé => minutes annoncées, null si
     * non applicable). Les libellés sont traduits côté app et page web.
     */
    public const RESPONSES = [
        'coming' => null,   // « J'arrive »
        'min5' => 5,
        'min10' => 10,
        'min15' => 15,
        'resolved' => null, // « C'est réglé »
    ];

    protected $table = 'qr_notifications';

    protected $fillable = [
        'public_id',
        'user_id',
        'message_key',
        'message_text',
        'ip_hash',
        'is_read',
        'read_at',
        'response_key',
        'responded_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    protected static function booted()
    {
        // Identifiant public pour le suivi par le signaleur
        static::creating(function (QrNotification $notification) {
            $notification->public_id ??= (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope : non lues uniquement
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /** Champs exposés au propriétaire (liste et détail des notifications). */
    public function toOwnerArray(): array
    {
        return [
            'id' => $this->id,
            'message_key' => $this->message_key,
            'message_text' => $this->message_text,
            'is_read' => $this->is_read,
            'read_at' => $this->read_at?->toIso8601String(),
            'response_key' => $this->response_key,
            'responded_at' => $this->responded_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }

    /** Statut exposé au signaleur anonyme (aucune donnée du propriétaire). */
    public function toReporterArray(): array
    {
        return [
            'is_read' => $this->is_read,
            'response_key' => $this->response_key,
            'response_minutes' => $this->response_key ? (self::RESPONSES[$this->response_key] ?? null) : null,
            'responded_at' => $this->responded_at?->toIso8601String(),
        ];
    }
}

<?php

// ============================================================
// app/Models/QrNotification.php
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrNotification extends Model
{
    protected $table = 'qr_notifications';

    protected $fillable = [
        'user_id',
        'message_key',
        'message_text',
        'ip_hash',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope : non lues uniquement
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}

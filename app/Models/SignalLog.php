<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignalLog extends Model
{
    protected $fillable = [
        'user_id',
        'message_key',
        'ip_hash',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

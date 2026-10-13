<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubscriptionPayment extends Model
{
    public const PENDING = 'pending';
    public const SUCCESS = 'success';
    public const FAILED = 'failed';

    protected $fillable = [
        'reference',
        'user_id',
        'package_id',
        'subscription_id',
        'provider',
        'provider_reference',
        'amount',
        'currency',
        'operator',
        'phone',
        'status',
        'failure_reason',
        'paid_at',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'meta' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function (SubscriptionPayment $payment) {
            $payment->reference ??= (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }

    /** Données exposées à l'app. */
    public function toApiArray(): array
    {
        return [
            'reference' => $this->reference,
            'status' => $this->status,
            'amount' => (int) $this->amount,
            'currency' => $this->currency,
            'operator' => $this->operator,
            'failure_reason' => $this->failure_reason,
            'paid_at' => $this->paid_at?->toIso8601String(),
        ];
    }
}

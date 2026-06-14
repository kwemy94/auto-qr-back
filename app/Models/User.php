<?php

namespace App\Models;

use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (!$user->end_trial_period) {
                $user->end_trial_period = now()->addDays(30);
            }
            $user->qr_code = self::generateUniqueQR();
            // $user->qr_code = self::generateUniqueQR().'-'. $user->fcm_token;
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function message_senders()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    public function message_receivers()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
    public function subscriptions()
    {
        return $this->belongsToMany(Package::class, 'subscriptions')
            ->withPivot('start_date', 'end_date')
            ->withTimestamps();
    }

    public static function generateUniqueQR()
    {
        do {
            $random = strtoupper(Str::random(10));
            $code = "US.QR-$random";
        } while (self::where('qr_code', $code)->exists());

        return $code;
    }
}

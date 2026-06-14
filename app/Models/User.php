<?php

namespace App\Models;

use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

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
        static::created(function ($user) {
            if (!$user->end_trial_period) {
                $user->end_trial_period = now()->addDays(30);
                $user->save();
            }
            self::generateUniqueQR($user);
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


    public static function generateUniqueQR(User $user): void
    {
        if (!empty($user->qr_path) && Storage::disk('public')->exists($user->qr_path)) {
            Storage::disk('public')->delete($user->qr_path);
        }

        $uuid = Str::uuid();
        $uniqueQRData = "US.QR-" . $user->id . '-' . $uuid;

        $qrImage = QrCode::format('png')->size(300)->generate($uniqueQRData);
        $qrImageFile = "qrcodes/{$uuid}.png";

        Storage::disk('public')->put($qrImageFile, $qrImage);

        $user->qr_code = $uniqueQRData;
        $user->qr_path = $qrImageFile;
        $user->save();
    }
}

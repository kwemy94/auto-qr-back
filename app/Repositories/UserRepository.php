<?php

namespace App\Repositories;

use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;

class UserRepository extends ResourceRepository
{

    protected User $user;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getUser($userId)
    {
        return $this->model->with('subscriptions')->where('id', $userId)->first();
    }

    public function subscribe(Package $package, $userId)
    {
        return $this->model->where('id', $userId)->first()->subscriptions()->attach(
            [
                $package->id => [
                    'start_date' => Carbon::now()->toDateTime(),
                    'end_date' => Carbon::now()->addMonths($package->duration)->toDateTimeString(),
                ],
            ],
        );
    }

    public function getLastSubscriptionForUser($userId)
    {
        return $this->getById($userId)->subscriptions()
            ->orderByPivot('created_at', 'desc')
            ->first();
    }

    public function hasExpiredLastestSubscription($userId)
    {
        $latest = $this->getLastSubscriptionForUser($userId);
        if (!$latest || empty($latest->pivot->end_date)) {
            return [
                'subscription' => null,
                'expired' => true,
            ];
        }
        $expired = Carbon::parse($latest->pivot->end_date)->isPast();
        return [
            'subscription' => $latest,
            'expired' => $expired,
        ];
    }

    public function getByQRCode(string $qr_code){
        return $this->model->where('qr_code', $qr_code)->first();
    }
}

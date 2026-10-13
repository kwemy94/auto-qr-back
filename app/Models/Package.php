<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Package extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'duration' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
    
    public function subscriptions(){
        return $this->belongsToMany(User::class, 'subscriptions')
            ->withPivot('start_date', 'end_date')
            ->withTimesTamps();
    }

    
}

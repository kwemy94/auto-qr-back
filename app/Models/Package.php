<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'duration' => 'integer',
    ];
    
    public function subscriptions(){
        return $this->belongsToMany(User::class, 'subscriptions')
            ->withPivot('start_date', 'end_date')
            ->withTimesTamps();
    }

    
}

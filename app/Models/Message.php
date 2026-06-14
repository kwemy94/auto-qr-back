<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Message extends Model
{
    protected $guarded = ['id'];

    public function user_sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function user_receiver(){
        return $this->belongsTo(User::class, 'receiver_id');
    }
     
    public function payments(){
        return $this->hasMany(Payment::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordOtp extends Model
{
    protected $fillable = [
        'email', 'otp', 'expires_at', 'last_sent_at', 'attempts'
    ];

    protected $dates = ['expires_at', 'last_sent_at'];
}

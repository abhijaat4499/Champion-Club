<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'otp_code', 'otp_expires_at', 'reset_otp_code', 'reset_otp_expires_at'];

    protected $hidden = ['password', 'otp_code', 'reset_otp_code'];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'reset_otp_expires_at' => 'datetime',
    ];
}

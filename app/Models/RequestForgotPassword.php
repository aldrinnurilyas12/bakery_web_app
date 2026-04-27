<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestForgotPassword extends Model
{
    protected $table = 'request_forgot_password';
    protected $fillable = [
        'email',
        'otp',
        'status',
        'expired',
        'request_date'
    ];
}

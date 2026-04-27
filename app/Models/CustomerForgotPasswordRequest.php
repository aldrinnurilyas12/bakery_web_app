<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerForgotPasswordRequest extends Model
{
    protected $table = 'customer_forgot_password_request';
    protected $fillable = [
        'customer',
        'email',
        'otp',
        'status',
        'expired',
        'request_date'
    ];
}

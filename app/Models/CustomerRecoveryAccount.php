<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRecoveryAccount extends Model
{
    protected $table = 'customer_recovery_account';
    protected $fillable = [
        'customer',
        'token_link',
        'status',
        'expired_at'
    ];
}

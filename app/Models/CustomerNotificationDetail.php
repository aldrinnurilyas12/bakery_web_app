<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNotificationDetail extends Model
{
    protected $table = 'customer_notifications_detail';
    protected $fillable = [
        'notif',
        'transaction',
        'reward',
        'voucher',
        'voucher_birthday'
    ];
}

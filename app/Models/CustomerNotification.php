<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNotification extends Model
{
    protected $table = 'customer_notifications';
    protected $fillable = [
        'customer',
        'title',
        'message',
        'category',
        'is_read',

    ];
}

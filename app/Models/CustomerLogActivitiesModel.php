<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLogActivitiesModel extends Model
{
    protected $table = 'customers_log_activities';
    protected $fillable = [
        'customer',
        'product',
        'variant',
        'category',
        'description'
    ];
}

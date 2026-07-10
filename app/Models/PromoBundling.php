<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoBundling extends Model
{
    protected $table = 'promo_bundling';
    protected $fillable = [
        'bundling_code',
        'bundling_name',
        'price',
        'quantity',
        'start_date',
        'end_date',
        'description',
        'status',
        'images',
        'created_by',
        'updated_by'
    ];
}

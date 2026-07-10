<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoBundlingDetail extends Model
{
    protected $table = 'promo_bundling_detail';
    protected $fillable = [
        'bundling_code',
        'product',
        'variant',
        'quantity'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPriceHistory extends Model
{
    protected $table = 'product_price_history';
    protected $fillable = [
        'product',
        'price_code',
        'variant',
        'hpp',
        'price',
        'discount',
        'price_after_discount',
        'business_effective_date',
        'status'
    ];
}

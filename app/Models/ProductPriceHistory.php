<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPriceHistory extends Model
{
    protected $table = 'product_price_history';
    protected $fillable = [
        'product',
        'variant',
        'hpp',
        'price_after',
        'discount_after',
        'price_after_discount_after',
        'business_effective_date_new',
        'price_before',
        'discount_before',
        'price_after_discount_before',
        'business_effective_date_old',
        'status'
    ];
}

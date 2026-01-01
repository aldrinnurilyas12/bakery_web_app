<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsVariant extends Model
{
    use HasFactory;
    protected $table='product_variant';
    protected $fillable = [
        'variant_code',
        'product',
        'variant_price',
        'variant_discount',
        'variant_price_after_discount',
        'variant_type',
        'created_at'
    ];
}

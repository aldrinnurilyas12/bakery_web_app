<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionProductDetailModel extends Model
{
    protected $table = 'production_products_detail';
    protected $fillable = [
        'production_code',
        'product',
        'variant',
        'actual_quantity',
        'qty_target_total',
        'reject_quantity'
    ];
}

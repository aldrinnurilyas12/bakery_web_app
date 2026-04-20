<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CentralStockProductsModel extends Model
{
    protected $table = 'central_stock_products';
    protected $fillable = [
        'production',
        'product',
        'variant',
        'qty_produced',
        'qty_available'
    ];
}

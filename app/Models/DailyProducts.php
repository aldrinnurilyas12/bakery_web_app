<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProducts extends Model
{
    use HasFactory;

    protected $table = 'products_daily';
    protected $fillable = [
        'product_code',
        'stock_available',
        'status',
        'point',
        'created_by',
        'updated_by'
    ];
}

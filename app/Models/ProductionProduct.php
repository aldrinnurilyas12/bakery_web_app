<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionProduct extends Model
{
    use HasFactory;

    protected $table = 'production_products';
    protected $fillable = [
        'production_code',
        'product',
        'target_total',
        'reject_quantity',
        'actual_quantity',
        'status',
        'production_type',
        'production_date',
        'created_by',
        'updated_by'
    ];
}

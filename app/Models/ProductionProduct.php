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
        'status',
        'production_type',
        'production_date',
        'reason_failed',
        'created_by',
        'updated_by'
    ];
}

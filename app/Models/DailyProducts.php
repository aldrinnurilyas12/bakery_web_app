<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProducts extends Model
{
    use HasFactory;

    protected $table = 'products_daily';
    protected $fillable = [
        'daily_code',
        'distribution_store',
        'stock_available',
        'status',
        'store',
        'created_by',
        'updated_by'
    ];
}

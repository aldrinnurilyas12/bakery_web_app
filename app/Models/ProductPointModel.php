<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPointModel extends Model
{
    use HasFactory;
    protected $table = 'products_point';
    protected $fillable = [
        'product',
        'point', 
        'start_date',
        'end_date',
        'status'
    ];
}

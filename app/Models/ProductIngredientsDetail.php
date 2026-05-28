<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductIngredientsDetail extends Model
{
    use HasFactory;
    protected $table = 'product_ingredients_detail';
    protected $fillable = [
        'ingredients',
        'raw_material',
        'quantity',
        'unit',
        'subtotal'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductIngredients extends Model
{
    use HasFactory;
    protected $table = 'product_ingredients';
    protected $fillable = [
        'ingredients_code',
        'product'

    ];
}

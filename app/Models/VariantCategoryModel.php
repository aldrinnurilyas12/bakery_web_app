<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantCategoryModel extends Model
{
    use HasFactory;

    protected $table = 'variant_category';
    protected $fillable  =[
        'name'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemsCategoryModel extends Model
{
    use HasFactory;
    protected $table = 'product_category';

    protected $fillable = [
        'category_name'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(ProductsModel::class, 'category_id');
    }
}

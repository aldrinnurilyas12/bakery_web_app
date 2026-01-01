<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductsModel extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'product_code',
        'product_name',
        'category_id',
        'price',
        'discount',
        'price_after_discount',
        'product_weight',
        'product_weight_type',
        'product_variant',
        'description',
        'expired_date',
        'created_by',
        'updated_by'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemsCategoryModel::class, 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReviews extends Model
{
    protected $table = 'product_reviews';
    protected $fillable = [
        'transaction',
        'product',
        'variant',
        'review',
        'rating',
        'hidden_name',
        'review_date'
    ];
}

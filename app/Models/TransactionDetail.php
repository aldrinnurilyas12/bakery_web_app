<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    protected $table = 'transactions_detail';
    protected $fillable = [
        'transaction_code',
        'product',
        'variant',
        'price',
        'quantity_per_product',
        'promo_bundling',
        'created_by',
        'updated_by'
    ];
}

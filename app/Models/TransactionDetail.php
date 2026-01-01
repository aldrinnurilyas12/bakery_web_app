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
        'quantity_per_product',
        'created_by',
        'updated_by'
    ];
}

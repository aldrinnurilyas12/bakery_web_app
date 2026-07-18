<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = [
        'transaction_code',
        'total_amount',
        'subtotal',
        'grand_total',
        'casheer',
        'customer',
        'status',
        'store',
        'payment_type',
        'payment_changes',
        'transaction_type',
        'transaction_date',
        'created_by',
        'updated_by'
    ];
}

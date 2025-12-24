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
        'quantity',
        'total_amount',
        'grand_total',
        'casheer',
        'customer',
        'status',
        'payment_type',
        'payment_changes',
        'promo_code',
        'reward_transaction_used',
        'transaction_date',
        'created_by',
        'updated_by'
    ];
}

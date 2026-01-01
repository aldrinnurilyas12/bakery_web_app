<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionsVouchers extends Model
{
    use HasFactory;
    protected $table = 'transactions_voucher';
    protected $fillable = [
        'transaction_code',
        'voucher_code',
        'status',
        'voucher_used',
        'used_at',
        'created_by',
        'updated_by'
    ];
}

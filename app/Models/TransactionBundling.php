<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionBundling extends Model
{
    protected $table = 'transactions_bundling';
    protected $fillable = [
        'transaction',
        'bundling',
        'transaction_date'
    ];
}

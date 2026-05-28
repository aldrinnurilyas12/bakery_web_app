<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraudTransactionTimeline extends Model
{
    protected $table = 'fraud_transactions_timeline';
    protected $fillable = [
        'fraud',
        'status',
        'updated_by'
    ];
}

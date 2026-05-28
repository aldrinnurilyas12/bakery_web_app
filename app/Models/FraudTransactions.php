<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraudTransactions extends Model
{
    protected $table = 'fraud_transactions';
    protected $fillable = [
        'fraud_code',
        'transaction',
        'fraud_type',
        'fraud_status_info',
        'severity_level',
        'status',
        'investigation_by',
        'approval_by',
        'notes',
        'it_testing',
        'it_testing_by',
    ];
}

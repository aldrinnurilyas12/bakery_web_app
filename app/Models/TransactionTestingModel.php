<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionTestingModel extends Model
{
    protected $table = 'testing_transactions';
    protected $fillable = [
        'transaction',
        'testing_date',
        'testing_by',
        'is_testing'
    ];
}

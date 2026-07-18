<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    protected $table = 'customer_feedback';
    protected $fillable = [
        'feedback_code',
        'transaction',
        'feedback_message',
        'feedback_date'
    ];
}

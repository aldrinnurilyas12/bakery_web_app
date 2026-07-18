<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerFeedbackDetail extends Model
{
    protected $table = 'customer_feedback_detail';
    protected $fillable = [
        'feedback',
        'feedback_type',
        
    ];
}

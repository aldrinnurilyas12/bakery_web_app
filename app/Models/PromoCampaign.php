<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCampaign extends Model
{
    use HasFactory;

    protected $table = 'promo_campaign';
    protected $fillable = [
        'promo_name',
        'promo_code',
        'product',
        'min_transaction',
        'description',
        'status',
        'quota',
        'start_date',
        'end_date',
        'created_by',
        'updated_by'
    ];
}

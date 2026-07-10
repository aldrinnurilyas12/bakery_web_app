<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedeemRewardModel extends Model
{
    use HasFactory;

    protected $table = 'redeem_reward';
    protected $fillable = [
        'redeem_code',
        'reward',
        'customer',
        'status',
        'pickup_schedule',
        'quantity',
        'redeem_date',
        'claimed_at'
    ];
}

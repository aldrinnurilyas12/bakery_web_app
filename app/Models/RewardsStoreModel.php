<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardsStoreModel extends Model
{
    use HasFactory;
    protected $table = 'rewards_store';
    protected $fillable = [
        'reward_store_code',
        'reward',
        'store',
        'stock',
        'status'
    ];
}

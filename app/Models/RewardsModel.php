<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardsModel extends Model
{
    use HasFactory;

    protected $table = 'rewards';
    protected $fillable = [
        'rewards_code',
        'rewards_name',
        'point', 
        'quota',
        'images',
        'status',
        'start_date',
        'end_date',
        'created_by',
        'updated_by'
    ];
}

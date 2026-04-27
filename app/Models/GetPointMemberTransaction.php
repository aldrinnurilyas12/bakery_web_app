<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GetPointMemberTransaction extends Model
{
    protected $table = 'point_member_transactions';
    protected $fillable = [
        'point',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'updated_by'
    ];
}

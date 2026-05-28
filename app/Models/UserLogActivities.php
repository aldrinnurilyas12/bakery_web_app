<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogActivities extends Model
{
    protected $table = 'user_log_activities';
    protected $fillable = [
        'user',
        'module',
        'method_type',
        'ip_address',
        'user_agent',
        'activity_date',
        'description'
    ];
}

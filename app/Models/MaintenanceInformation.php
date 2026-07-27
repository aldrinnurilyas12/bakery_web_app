<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceInformation extends Model
{
    protected $table = 'maintenance_information';
    protected $fillable = [
        'info_code',
        'maintenance_information',
        'message',
        'status',
        'type',
        'start_date',
        'hour_start',
        'end_date',
        'hour_end',
        'created_by',
        'updated_by'
    ];
}

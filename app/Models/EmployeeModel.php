<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model
{
    use HasFactory;

    protected $table = 'employee';
    protected $fillable = [
        'nik',
        'name',
        'birth_date',
        'address',
        'phone_number',
        'email', 
        'position',
        'branch',
        'start_date',
        'end_date',
        'created_by',
        'updated_by'
    ];
}

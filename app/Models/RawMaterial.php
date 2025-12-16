<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $table = 'raw_material';
    protected $fillable = [
        'material_code',
        'material_name',
        'quantity',
        'price',
        'material_type',
        'expired_date',
        'status',
        'created_by',
        'updated_by'
        
    ];
}

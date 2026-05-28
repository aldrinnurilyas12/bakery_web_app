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
        'purchase_unit',
        'inventory_unit',
        'material_category',
        'expired_date',
        'status',
        'created_by',
        'updated_by'
        
    ];
}

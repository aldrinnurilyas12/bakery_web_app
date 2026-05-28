<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialUsages extends Model
{
    use HasFactory;

    protected $table = 'raw_material_usages';
    protected $fillable = [
        'production_code',
        'raw_material',
        'quantity_used',
        'unit',
        'created_by',
        'updated_by'
    ];
}

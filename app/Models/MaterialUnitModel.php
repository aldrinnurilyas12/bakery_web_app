<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialUnitModel extends Model
{
    protected $table = 'material_unit_category';
    protected $fillable = [
        'unit_code',
        'unit_name'
    ];
}

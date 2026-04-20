<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsModel extends Model
{
    protected $table = 'items';
    protected $fillable = [
        'item_code',
        'raw_material',
        'name',
        'item_category',
        'weight_type',
        'created_by',
        'updated_by'
    ];
}

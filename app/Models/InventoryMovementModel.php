<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovementModel extends Model
{
    protected $table = 'inventory_movement';
    protected $fillable = [
        'inventory_code_reference',
        'purchase_code',
        'distribution',
        'movement_type',
        'references_type',
        'movement_date',
        'status'
    ];
}

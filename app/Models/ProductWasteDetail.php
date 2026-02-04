<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWasteDetail extends Model
{
    use HasFactory;

    protected $table = 'product_wastes_detail';
    protected $fillable = [
        'waste_code',
        'waste_type',
        'quantity'
    ];

}

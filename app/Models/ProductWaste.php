<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWaste extends Model
{
    use HasFactory;
    protected $table = 'product_wastes';
    protected $fillable = [
        'waste_code',
        'production_code',
        'product_daily',
        'attachment_files',
        'distribution',
        'quantity',
        'reason',
        'waste_date',
        'approved_by',
        'status',
        'created_by',
        'updated_by'
    ];
}

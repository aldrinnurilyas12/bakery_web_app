<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionProductsModel extends Model
{
    protected $table = 'distribution_products';
    protected $fillable = [
        'distribution_code',
        'distribution_date',
        'status',
        'notes',
        'attachment_files',
        'created_by',
        'updated_by'
    ];
}

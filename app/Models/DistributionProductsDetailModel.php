<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionProductsDetailModel extends Model
{
    protected $table = 'distribution_products_detail';
    protected $fillable = [
        'distribution_store_code',
        'distribution',
        'product',
        'variant',
        'quantity',
        'received_quantity',
        'reject_quantity',
        'expired_date',
        'notes',
        'store',
        'status',
        'approval',
        'product_daily',
        'expired_status',
        'received_date',
        'attachment_files'
    ];
}

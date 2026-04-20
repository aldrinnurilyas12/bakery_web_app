<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderModel extends Model
{
    protected $table = 'purchase_order';
    protected $fillable = [
        'purchase_code',
        'purchase_date',
        'supplier',
        'status',
        'delivery',
        'total_amount',
        'payment_invoice',
        'expected_delivery_date',
        'created_by',
        'updated_by'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetailModel extends Model
{
    protected $table = 'purchase_order_detail';
    protected $fillable =[
        'purchase_code',
        'item',
        'raw_material',
        'quantity',
        'price',
        'expired_date'
    ];
}

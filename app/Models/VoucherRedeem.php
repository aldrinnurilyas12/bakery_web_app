<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherRedeem extends Model
{
    use HasFactory;
    protected $table = 'redeem_voucher';
    protected $fillable = [
        'voucher_code',
        'customer_voucher',
        'customer',
        'redeem_date',
        'casheer',
        'status',
        'store',
        'created_by',
        'updated_by'
    ];
}

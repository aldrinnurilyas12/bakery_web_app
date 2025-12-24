<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherCustomer extends Model
{
    use HasFactory;
    protected $table = 'customer_vouchers';
    protected $fillable = [
        'customer',
        'voucher',
        'transaction',
        'status',
        'voucher_used',
        'created_by',
        'updated_by'
    ];
}

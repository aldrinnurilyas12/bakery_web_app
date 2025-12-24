<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VouchersUsages extends Model
{
    use HasFactory;
    
    protected $table ='vouchers_usages';
    protected $fillable = [
        'voucher_code',
        'customer',
        'voucher_used',
        'used_at'
    ];
}

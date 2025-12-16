<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherModel extends Model
{
    use HasFactory;
    protected $table = 'voucher';
    protected $fillable = [
        'voucher_code',
        'voucher_name',
        'quota',
        'nominal',
        'discount',
        'min_transaction',
        'status',
        'qr_code',
        'start_date',
        'end_date',
        'created_by',
        'updated_by'
    ];
}

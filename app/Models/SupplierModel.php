<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;
    protected $table = 'supplier';
    protected $fillable = [
        'supplier_code',
        'store',
        'phone_number',
        'address',
        'pic',
        'payment_terms',
        'supplier_category',
        'status'
    ];
}

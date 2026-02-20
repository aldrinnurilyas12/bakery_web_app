<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletStoreModel extends Model
{
    use HasFactory;
    protected $table = 'store';
    protected $fillable = [
        'store_name',
        'store_code',
        'location',
        'head_of_branch',
        'latitude',
        'longitude',
        'status'
    ];
}

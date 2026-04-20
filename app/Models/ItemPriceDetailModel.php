<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPriceDetailModel extends Model
{
    protected $table = 'item_price_detail';
    protected $fillable = [
        'item',
        'price'
    ];
}

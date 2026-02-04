<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCampaignProducts extends Model
{
    use HasFactory;
    protected $table = 'promo_campaign_products';
    protected $fillable = [
        'promo_code',
        'product',
        'variant'
    ];
}

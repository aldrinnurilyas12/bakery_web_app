<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCampaignImages extends Model
{
    use HasFactory;

    protected $table = 'promo_campaign_images';
    protected $fillable = [
        'promo_code',
        'images'
    ];
}

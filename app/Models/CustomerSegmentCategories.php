<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSegmentCategories extends Model
{
    protected $table ='customer_segment';
    protected $fillable = [
        'segment_name',
        'min_transaction',
        'max_transaction',
        'min_spent',
        'max_spent',
        'recency',
        'indicator',
        'color',
        'sort_order',
        'status',
        'created_by',
        'updated_by'
    ];
}

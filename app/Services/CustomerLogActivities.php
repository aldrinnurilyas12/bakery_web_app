<?php 

namespace App\Services;

use App\Models\CustomerLogActivitiesModel;

class CustomerLogActivities{
    
    public static function log(
        ?string $customer = null,
        ?string $product = null,
        ?string $variant = null,
        ?string $category = null,
        ?string $description = null,
    ): void {
        CustomerLogActivitiesModel::create([
            'customer' => $customer,
            'product' => $product,
            'variant' => $variant,
            'category' => $category,
            'description' => $description,
            'created_at' => now()
        ]);
    }
}

?>
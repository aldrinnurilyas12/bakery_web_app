<?php 

namespace App\Services;

use App\Models\CustomerNotification as ModelCustomerNotification;
use App\Models\CustomerNotificationDetail;

class CustomerNotification{
    
    public static function log(
        ?string $customer = null,
        ?string $title = null,
        ?string $message = null,
        ?string $category = null,
        ?string $is_read = null,
        ?string $transaction = null,
        ?string $reward = null,
        ?string $voucher = null
    ): void {
        
      $notif  = ModelCustomerNotification::create([
            'customer' => $customer,
            'title' => $title,
            'message' => $message,
            'category' =>$category,
            'is_read' => $is_read
        ]);

        CustomerNotificationDetail::create([
            'notif' => $notif->id,
            'transaction' => $transaction,
            'reward' => $reward,
            'voucher' => $voucher
        ]);
    }
}
?>
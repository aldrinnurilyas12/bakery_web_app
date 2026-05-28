<?php 

namespace App\Services;

use App\Models\UserLogActivities;

class UserLogActivity{
    
    public static function log(
        ?string $module = null,
        ?string $description = null,
        ?string $method_type = null
    ): void {
        $user = auth()->user();
        UserLogActivities::create([
            'user' => $user ?->nik,
            'module' => $module,
            'method_type' => $method_type,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'activity_date' => now(),
            'description' =>$description
        ]);
    }
}






?>
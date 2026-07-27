<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'user_permission_access';
    protected $fillable = [
        'submenu',
        'role'
    ];
}

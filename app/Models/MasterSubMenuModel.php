<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSubMenuModel extends Model
{
    use HasFactory;
    protected $table = 'submenu';
    protected $fillable = [
        'submenu_name',
        'submenu_link',
        'main_menu',
        'icon',
        'status',
        'allow_access_outside_operational_hours',
        'description'
    ];
}

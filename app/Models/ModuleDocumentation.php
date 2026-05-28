<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleDocumentation extends Model
{

    protected $table = 'module_documentation';
    protected $fillable = [
        'module_name',
        'attachment_file',
        'url_path',
        'description'

    ];
}

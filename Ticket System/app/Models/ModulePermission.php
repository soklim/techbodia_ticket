<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{
    use HasFactory;
    protected $table = 'module_permissions';
    protected $fillable = [
        'module_id', 'role_id','a_create','a_read','a_update','a_delete'
    ];

}

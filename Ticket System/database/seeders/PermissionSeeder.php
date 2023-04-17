<?php

namespace Database\Seeders;

use App\Models\ModulePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends  Seeder
{
    public  function run(){
        //Permission Seeder
        //php artisan db:seed --class=PermissionSeeder
        ModulePermission::create([
            'module_id' => 1,
            'role_id' => 1,
            'a_create' => 1,
            'a_read' => 1,
            'a_update' => 1,
            'a_delete' => 1,
        ]);
        ModulePermission::create([
            'module_id' => 2,
            'role_id' => 1,
            'a_create' => 1,
            'a_read' => 1,
            'a_update' => 1,
            'a_delete' => 1,
        ]);
        ModulePermission::create([
            'module_id' => 3,
            'role_id' => 1,
            'a_create' => 1,
            'a_read' => 1,
            'a_update' => 1,
            'a_delete' => 1,
        ]);
        ModulePermission::create([
            'module_id' => 4,
            'role_id' => 1,
            'a_create' => 1,
            'a_read' => 1,
            'a_update' => 1,
            'a_delete' => 1,
        ]);
        ModulePermission::create([
            'module_id' => 5,
            'role_id' => 1,
            'a_create' => 1,
            'a_read' => 1,
            'a_update' => 1,
            'a_delete' => 1,
        ]);
        ModulePermission::create([
            'module_id' => 6,
            'role_id' => 1,
            'a_create' => 1,
            'a_read' => 1,
            'a_update' => 1,
            'a_delete' => 1,
        ]);
        ModulePermission::create([
            'module_id' => 7,
            'role_id' => 1,
            'a_create' => 1,
            'a_read' => 1,
            'a_update' => 1,
            'a_delete' => 1,
        ]);
        ModulePermission::create([
            'module_id' => 8,
            'role_id' => 1,
            'a_create' => 1,
            'a_read' => 1,
            'a_update' => 1,
            'a_delete' => 1,
        ]);
        ModulePermission::create([
            'module_id' => 9,
            'role_id' => 1,
            'a_create' => 1,
            'a_read' => 1,
            'a_update' => 1,
            'a_delete' => 1,
        ]);
        ModulePermission::create([
            'module_id' => 10,
            'role_id' => 1,
            'a_create' => 1,
            'a_read' => 1,
            'a_update' => 1,
            'a_delete' => 1,
        ]);
    }
}

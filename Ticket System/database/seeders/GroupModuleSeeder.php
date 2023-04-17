<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\GroupModule;
use App\Models\Module;

class GroupModuleSeeder extends Seeder
{
    public function run()
    {
        //Group Module
        //php artisan db:seed --class=GroupModuleSeeder
        GroupModule::create([
            'name' => 'Security',
            'icon' => 'shield-quarter'
        ]);
        GroupModule::create([
            'name' => 'Setting',
            'icon' => 'cog'
        ]);
        //Module Group1
        Module::create([
            'name' => 'Role',
            'group_id' => 1,
            'route_name' => 'roles',
            'active' => 1,
        ]);
        Module::create([
            'name' => 'User',
            'group_id' => 1,
            'route_name' => 'users',
            'active' => 1,
        ]);
        Module::create([
            'name' => 'Group Module',
            'group_id' => 1,
            'route_name' => 'group_modules',
            'active' => 1,
        ]);
        Module::create([
            'name' => 'Module',
            'group_id' => 1,
            'route_name' => 'modules',
            'active' => 1,
        ]);

        //Module Group2
        Module::create([
            'name' => 'Setting Type',
            'group_id' => 2,
            'route_name' => 'setting_types',
            'active' => 1,
        ]);
        Module::create([
            'name' => 'Setting Item',
            'group_id' => 2,
            'route_name' => 'setting_items',
            'active' => 1,
        ]);
        Module::create([
            'name' => 'Province',
            'group_id' => 2,
            'route_name' => 'provinces',
            'active' => 1,
        ]);
        Module::create([
            'name' => 'District',
            'group_id' => 2,
            'route_name' => 'districts',
            'active' => 1,
        ]);
        Module::create([
            'name' => 'Commune',
            'group_id' => 2,
            'route_name' => 'communes',
            'active' => 1,
        ]);
        Module::create([
            'name' => 'Village',
            'group_id' => 2,
            'route_name' => 'villages',
            'active' => 1,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Admin Seeder
        //php artisan db:seed --class=CreateAdminUserSeeder
        User::create([
            'name' => 'Soklim007',
            'email' => 'soklim.kheng@gmail.com',
            'role_id' => 1,
            'password' => bcrypt('1234.com@@@')
        ]);

        Role::create(['name' => 'Admin']);
//        $permissions = Permission::pluck('id','id')->all();
//
//        $role->syncPermissions($permissions);
//
//        $user->assignRole([$role->id]);
    }
}

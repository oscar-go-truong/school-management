<?php

namespace Database\Seeders;

use App\Enums\UserRoleNameContants;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => UserRoleNameContants::ADMIN]);  
        Role::create(['name' => UserRoleNameContants::TEACHER]);  
        Role::create(['name' => UserRoleNameContants::STUDENT]);  
    }
}

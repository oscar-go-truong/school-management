<?php

namespace Database\Seeders;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleContants;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('users')->insert([
            'username' => 'admin',
            'fullname' => 'admin',
            'email' => 'admin@gmail.com',
            'status' => StatusTypeContants::ACTIVE,
            'role' => UserRoleContants::ADMIN,
            'password' => Hash::make('password'),
            'phone' => $faker->phoneNumber(),
            'mobile' => $faker->phoneNumber(),
            'address' => $faker->address()
        ]);
        DB::table('users')->insert([
            'username' => 'teacher',
            'fullname' => 'teacher',
            'email' => 'teacher@gmail.com',
            'status' => StatusTypeContants::ACTIVE,
            'role' => UserRoleContants::TEACHER,
            'password' => Hash::make('password'),
            'phone' => $faker->phoneNumber(),
            'mobile' => $faker->phoneNumber(),
            'address' => $faker->address()
        ]);
        DB::table('users')->insert([
            'username' => 'student',
            'fullname' => 'student',
            'email' => 'student@gmail.com',
            'status' => StatusTypeContants::ACTIVE,
            'role' => UserRoleContants::STUDENT,
            'password' => Hash::make('password'),
            'phone' => $faker->phoneNumber(),
            'mobile' => $faker->phoneNumber(),
            'address' => $faker->address()
        ]);
        
    User::factory()
            ->count(100)
            ->create();
    }
}

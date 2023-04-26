<?php

namespace Database\Seeders;

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
            'status' => 1,
            'role' => 1,
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

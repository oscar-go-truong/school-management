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
        $admin = User::create([
            'username' => 'admin',
            'fullname' => 'admin',
            'email' => 'admin@gmail.com',
            'status' => StatusTypeContants::ACTIVE,
            'password' => Hash::make('password'),
            'phone' => $faker->phoneNumber(),
            'mobile' => $faker->phoneNumber(),
            'address' => $faker->address()
        ]);
        $admin->assignRole('admin');
        $teacher = User::create([
            'username' => 'teacher',
            'fullname' => 'teacher',
            'email' => 'teacher@gmail.com',
            'status' => StatusTypeContants::ACTIVE,
            'password' => Hash::make('password'),
            'phone' => $faker->phoneNumber(),
            'mobile' => $faker->phoneNumber(),
            'address' => $faker->address()
        ]);
        $teacher->assignRole('teacher');
        $student = User::create([
            'username' => 'student',
            'fullname' => 'student',
            'email' => 'student@gmail.com',
            'status' => StatusTypeContants::ACTIVE,
            'password' => Hash::make('password'),
            'phone' => $faker->phoneNumber(),
            'mobile' => $faker->phoneNumber(),
            'address' => $faker->address()
        ]);
        $student->assignRole('student');
    User::factory()
            ->count(100)
            ->create();
    $users = User::whereNotIn('id',[1,2,3])->get();
    foreach($users as $user)
    {
        $role = collect(['admin', 'teacher', 'student'])->random();
        $user->assignRole($role);
    }
    }
    
}

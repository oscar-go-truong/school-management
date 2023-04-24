<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserCourse;

class UserCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserCourse::factory()->count(500)->create();
    }
}

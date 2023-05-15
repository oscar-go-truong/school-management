<?php

namespace Database\Seeders;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleNameContants;
use App\Models\Course;
use App\Models\User;
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
       for($i = 0;$i<300;$i++)
       {
        do{
            $userId = User::role(UserRoleNameContants::STUDENT)->inRandomOrder()->first()->id;
            $courseId = Course::all()->random()->id;
        } while(UserCourse::where('user_id', $userId)->where('course_id', $courseId)->count());
        UserCourse::insert([
            'user_id' => $userId,
            'course_id' => $courseId,
            'status' => StatusTypeContants::getRandomValue(),
        ]);
       }
    }
}

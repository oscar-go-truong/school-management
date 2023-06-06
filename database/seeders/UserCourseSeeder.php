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
            $course = Course::all()->random();
            $isValidCourse = UserCourse::where('user_id', $userId)
                                        ->where('course_id', $course->id)
                                        ->count() && UserCourse::where('user_id', $userId)
                                        ->whereHas('course', function($query) use($course){
                                            $query->where('subject_id'.$course->subject_id);
            });
        } while($isValidCourse);
        UserCourse::insert([
            'user_id' => $userId,
            'course_id' => $course->id,
            'status' => StatusTypeContants::ACTIVE,
        ]);
       }
    }
}

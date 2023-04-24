<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\UserCourse;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::factory()->count(100)->create();
        $courses = Course::all();
        foreach($courses as $course){
            UserCourse::insert(array('user_id'=> $course->owner_id, 'course_id'=>$course->id, 'role'=> UserRole::TEACHER, 'status'=>1));
        }
    }
}

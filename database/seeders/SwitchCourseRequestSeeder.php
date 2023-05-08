<?php

namespace Database\Seeders;

use App\Enums\RequestTypeContants;
use App\Models\Course;
use App\Models\Request;
use App\Models\UserCourse;
use Illuminate\Database\Seeder;

class SwitchCourseRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requests = Request::where('type', RequestTypeContants::SWITCH_COURSE)->get();
        foreach($requests as $request)
        {
            $courseId = UserCourse::where('user_id',3)->get()->random()->course_id;
            $oldCourse = Course::find($courseId);
            $newCourse = Course::where('subject_id', $oldCourse->subject_id)->where('id','!=',$oldCourse->id)->whereDoesntHave('userCourse', function($query){
                $query->where('user_id',3);
            })->first();
            $content = 
                '{"old_course_id":'.$oldCourse->id.
                ',"new_course_id":'.$newCourse->id.
                ',"reason":'.'"Conflict schedule with other course."}';
            Request::where('id',$request->id)->update( ['content' => $content]);
        }
    }
}

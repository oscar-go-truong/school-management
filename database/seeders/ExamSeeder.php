<?php

namespace Database\Seeders;

use App\Enums\UserRoleNameContants;
use App\Models\Course;
use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Score;
use App\Models\UserCourse;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Exam::factory()->count(100)->create();
        $exams = Exam::with('course')->get();
        foreach($exams as $exam) 
        {
            $students = UserCourse::where('course_id', $exam->course_id)->whereHas('user', function ($query) {
                $query->role(UserRoleNameContants::STUDENT);
            })->get();
            foreach($students as $student)
            {
                $course = Course::find($exam->course_id);
                Score::insert(
                [
                'student_id'=> $student->user_id, 
                'exam_id' => $exam->id,
                'created_at'=>$course->created_at,
                'total' => collect([0,5,7,8,9,10])->random(),
                'updated_by' => $course->owner_id
                ]
            );
            }
        }
    }
}

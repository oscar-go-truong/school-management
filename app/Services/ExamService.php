<?php

namespace App\Services;

use App\Enums\MyExamTypeConstants;
use App\Enums\UserRoleContants;
use App\Models\Exam;
use App\Models\Score;
use App\Models\UserCourse;
use Illuminate\Support\Facades\Auth;

class ExamService extends BaseService
{
    public function getModel()
    {
        return Exam::class;
    }

    public function getTable($request)
    {
        $user = Auth::user();
        $query = $this->model->withCount('score')->with('course.subject');
        $courseId = $request->query('courseId');
        if($courseId != null)
            $query = $query->where('course_id', $courseId);
        if(!$user->isAdministrator()){
            $query = $query->whereHas('course.userCourse', function ($query) use($user){
                $query->where('user_id', $user->id);
            });
        if($user->isStudent()){
            $query = $query->with('score', function ($query) use($user) {
                $query->where('student_id', $user->id);
            });
        }
        }
        $exams = $this->orderNSearch($request, $query);
        foreach ($exams as $exam) {
            $exam->type = MyExamTypeConstants::getKey($exam->type);
        }
        return $exams;
    }
    public function store($input) {
        $exam= $this->model->create($input);
        $resp = ['data' => $exam,'message' => ''];
        if($exam)
        {
            $userCourses = UserCourse::where('course_id', $exam->course_id)->whereHas('user', function ($query) {
                $query->where('role', UserRoleContants::STUDENT);
            })->get();;
            foreach($userCourses as $userCourse){
                Score::insert([
                    'student_id' => $userCourse->user_id,
                    'exam_id' => $exam->id
                ]);
            }
            $resp['message'] = 'Create successful!';  
        }
        $resp['error'] = 'Error, please try again later!'; 
        return $resp;
    }
}

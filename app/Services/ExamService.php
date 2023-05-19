<?php

namespace App\Services;

use App\Enums\MyExamTypeConstants;
use App\Enums\RequestStatusContants;
use App\Enums\RequestTypeContants;
use App\Enums\UserRoleNameContants;
use App\Helpers\Message;
use App\Jobs\PreventUpdateExamScores;
use App\Models\Exam;
use App\Models\Request;
use App\Models\Score;
use App\Models\UserCourse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamService extends BaseService
{
    protected $requestModel;

    public function __construct(Request $requestModel)
    {
        parent::__construct();
        $this->requestModel = $requestModel;
    }
    public function getModel()
    {
        return Exam::class;
    }

    public function getById($examId)
    {
        $exam = $this->model->with('course.subject')->find($examId);
        $exam->type = ucfirst(strtolower(MyExamTypeConstants::getKey($exam->type)));
        $exam->isRequested = $this->requestModel->where('type', RequestTypeContants::EDIT_EXAMS_SCORES)->where('status', RequestStatusContants::PENDING)->whereRaw("JSON_EXTRACT(content, '$.exam_id') = ". $examId)->count();
        return $exam;
    }

    public function getTable($request)
    {
        $user = Auth::user();
        $userIsAdmin = $user->hasRole(UserRoleNameContants::ADMIN);
        $userIsStudent = $user->hasRole(UserRoleNameContants::STUDENT);
        $courseId = $request->courseId;
        $year = $request->year;
        $query = $this->model->withCount('scores')->with('course.subject')->course($courseId)->year($year);
        
        if(!$userIsAdmin){
            $query = $query->whereHas('course.userCourses', function ($query) use($user){
                $query->where('user_id', $user->id);
            });
        if($userIsStudent){
            $query = $query->with('scores', function ($query) use($user) {
                $query->where('student_id', $user->id);
            });
        }
        }
        $result = $this->orderNSearch($request, $query);
        $exams = $result['data'];
        $data = [];
        foreach ($exams as $exam) {
            $item = [
                'id' => $exam->id,
                'subject' => $exam->course->subject->name,
                'course' => $exam->course->name,
                'type' => ucfirst(strtolower(MyExamTypeConstants::getKey($exam->type))),
                'scoresCount' => $exam->scores_count,
                'created_at' => $exam->created_at
            ];
            if($userIsStudent);
                {
                    $item['myScore'] = count($exam->scores) ? $exam->scores[0]->total : ""; 
                    $status = $this->requestModel->where('user_request_id', $user->id)->where('status','!=',RequestStatusContants::CANCELED)->whereRaw("JSON_EXTRACT(content, '$.exam_id') = ?", [$exam->id])->first();
                    $item['requestStatus'] = $status ? ucfirst(strtolower(RequestStatusContants::getKey($status->status))):null;
                }
            $data[] = $item;
        }
        $result['data'] = $data;
        return $result;
    }
}

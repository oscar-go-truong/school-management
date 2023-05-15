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
        $exam->isRequested = $this->requestModel->where('type', RequestTypeContants::EDIT_EXAMS_SCORES)->where('status', RequestStatusContants::PENDING)->whereRaw("JSON_EXTRACT(content, '$.exam_id') = ". $examId)->count();
        return $exam;
    }

    public function getTable($input)
    {
        $user = Auth::user();
        $courseId = isset($input['courseId'])?$input['courseId']:null;
        $query = $this->model->withCount('score')->with('course.subject');
        if($courseId != null)
            $query = $query->where('course_id', $courseId);
        if(!$user->hasRole(UserRoleNameContants::ADMIN)){
            $query = $query->whereHas('course.userCourse', function ($query) use($user){
                $query->where('user_id', $user->id);
            });
        if($user->hasRole(UserRoleNameContants::STUDENT)){
            $query = $query->with('score', function ($query) use($user) {
                $query->where('student_id', $user->id);
            });
        }
        }
        $exams = $this->orderNSearch($input, $query);
        foreach ($exams as $exam) {
            $exam->type = MyExamTypeConstants::getKey($exam->type);
            $exam->wasRequestedByUser = $this->requestModel->where('user_request_id', $user->id)->where('status',RequestStatusContants::PENDING)->whereRaw("JSON_EXTRACT(content, '$.exam_id') = ?", [$exam->id])->count();
            $exam->wasApprovedRequestedByAdmin = $this->requestModel->where('user_request_id', $user->id)->where('status',RequestStatusContants::APPROVED)->whereRaw("JSON_EXTRACT(content, '$.exam_id') = ?", [$exam->id])->count();
        }
        return $exams;
    }
    public function store($input) {
        try{
            DB::beginTransaction();
            $exam= $this->model->create($input);
            PreventUpdateExamScores::dispatch($exam->id)->delay(now()->addDay());
            $userCourses = UserCourse::where('course_id', $exam->course_id)->whereHas('user', function ($query) {
                $query->role(UserRoleNameContants::STUDENT);
            })->get();;
            foreach($userCourses as $userCourse){
                Score::insert([
                    'student_id' => $userCourse->user_id,
                    'exam_id' => $exam->id
                ]);
            }
            DB::commit();
        return ['data' => $exam,'message' => Message::createSuccessfully('exam')];
        } catch(Exception $e) 
        {
            DB::rollBack();
            return ['data' => null,'message' => Message::error()];
        }
    }
}

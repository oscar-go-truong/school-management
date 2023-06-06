<?php

namespace App\Services;

use App\Enums\MyExamTypeConstants;
use App\Enums\RequestStatusContants;
use App\Enums\RequestTypeContants;
use App\Enums\UserRoleNameContants;
use App\Models\Exam;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;

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
        $exam->isRequested = $this->requestModel
                            ->where('type', RequestTypeContants::EDIT_EXAM_SCORES)
                            ->where('status', RequestStatusContants::PENDING)
                            ->where("content->exam_id", $examId)->count();
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

        if (!$userIsAdmin) {
            $query = $query->where(function ($query) use ($user) {
                $query->whereHas('course.userCourses', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->orWhereHas('scores', function ($query) use ($user) {
                    $query->where('student_id', $user->id);
                });
            });
            if ($userIsStudent) {
                $query = $query->with('scores', function ($query) use ($user) {
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
            ];
            if ($userIsStudent) {
            }
                {
                    $item['myScore'] = count($exam->scores) ? $exam->scores[0]->total : "";
                    $item['marker'] = count($exam->scores) ? $exam->scores[0]->marker->fullname : "";
                    $item['isUpdated'] = count($exam->scores) && !$exam->scores[0]->edit_key && $this->requestModel->where('user_request_id', $user->id)->where('status', RequestStatusContants::APPROVED)->where("content->exam_id", [$exam->id])->first() ? true : false;
                    $status = $this->requestModel->where('user_request_id', $user->id)->where('status', '!=', RequestStatusContants::CANCELLED)->where("content->exam_id", [$exam->id])->first();
                    $item['requestStatus'] = $status ? ucfirst(strtolower(RequestStatusContants::getKey($status->status))) : null;
                }
            $data[] = $item;
        }
        $result['data'] = $data;
        return $result;
    }
}

<?php

namespace App\Services;

use App\Enums\MyExamTypeConstants;
use App\Enums\RequestStatusContants;
use App\Enums\RequestTypeContants;
use App\Enums\UserRoleNameContants;
use App\Helpers\Message;
use App\Jobs\PreventUpdateExamScores;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Request;
use App\Models\Room;
use App\Models\User;
use App\Models\UserCourse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestService extends BaseService
{
    protected $userCourseModel;
    protected $roomModel;
    protected $courseModel;
    protected $userModel;
    protected $examModel;

    public function __construct(UserCourse $userCourseModel, Room $roomModel, Course $courseModel, User $userModel, Exam $examModel)
    {
        parent::__construct();
        $this->userCourseModel = $userCourseModel;
        $this->roomModel = $roomModel;
        $this->courseModel = $courseModel;
        $this->userModel = $userModel;
        $this->examModel = $examModel;
    }
    public function getModel()
    {
        return Request::class;
    }

    public function getTable($input)
    {
        $query = $this->model->with('userRequest')->with('userApprove')->status($input)->type($input);
        if (!Auth::user()->hasRole(UserRoleNameContants::ADMIN)) {
            $query = $query->where('user_request_id', Auth::user()->id);
        }
        $requests = $this->orderNSearch($input, $query);
        foreach ($requests as $request) {
            $type = RequestTypeContants::getKey($request->type);
            $type = str_replace('_', ' ', $type);
            $type = strtolower($type);
            $type = ucwords($type);
            $request->type = $type;
        }

        return $requests;
    }

    public function storeApprovedSwitchClassRequest($input)
    {
        $user = Auth::user();
        $arg = [
                                'user_request_id' =>  $input['user_request_id'], 
                                'user_approve_id' => $user->id, 
                                'type' => RequestTypeContants::SWITCH_COURSE, 
                                'status' => RequestStatusContants::APPROVED, 
                                'old_course_id' => $input['oldCourseId'], 
                                'new_course_id' => $input['newCourseId'] 
                              ];
            $request = $this->model->create([
                "user_request_id" => $arg['user_request_id'],
                "user_approve_id" => $arg['user_approve_id'],
                "type" => $arg['type'],
                "status" => $arg['status'],
                "content" => '{"old_course_id":' . $arg['old_course_id'] . ',"new_course_id":' . $arg['new_course_id'] . ',"reason":"Admin change course."}'
                ]);
            return ['data' => $request];
    }

    public function storeReviewScoreRequest($input)
    {
        
        $examId = $input['exam_id'];
        $userId = Auth::user()->id;
        $checkIsExistRequest = $this->model->where('user_request_id', $userId)->where('content->exam_id', $examId)->where('type', RequestTypeContants::REVIEW_GRADES)->count();
        if($checkIsExistRequest)
            return ['data'=>null, 'message' => 'Request was created before!'];
        $request = $this->model->create([
            'user_request_id' => $userId,
            'status' => RequestStatusContants::PENDING,
            'type' => RequestTypeContants::REVIEW_GRADES,
            'content' => '{"exam_id":' . $examId . '}'
            ]);
        if ($request) {
            return ['data' => $request, 'message' => Message::createSuccessfully('request')];
        } else {
            return ['data' => $request, 'message' => Message::error()];
        }
    }

    public function storeSwitchcCourseRequest($input)
    {
        $oldCourseId = $input['old_course_id'];
        $newCourseId = $input['new_course_id'];
        $reason = $input['reason'];
        $userId = Auth::user()->id;
        $checkIsExistRequest = $this->model->where('user_request_id', $userId)->where('content->old_course_id', $oldCourseId)->where('type',RequestTypeContants::SWITCH_COURSE)->where('status', RequestStatusContants::PENDING)->count();
        if($checkIsExistRequest)
            return ['data'=>null, 'message' => 'Request was created before!'];
        $request = $this->model->create([
            'user_request_id' => $userId,
            'status' => RequestStatusContants::PENDING,
            'type' => RequestTypeContants::SWITCH_COURSE,
            'content' => '{"old_course_id":' . $oldCourseId.',"new_course_id":'.$newCourseId . ',"reason":"'.$reason.'"}'
        ]);
        if ($request) {
            return ['data' => $request, 'message' => Message::createSuccessfully('request')];
        } else {
            return ['data' => $request, 'message' => Message::error()];
        }
    }


    public function storeEditExamScoresRequest($input) 
    {
        $userId = Auth::user()->id;
        $examId = $input['exam_id'];
        $checkIsExistRequest = $this->model->where('user_request_id', $userId)->where('content->exam_id', $examId)->where('status', RequestStatusContants::PENDING)->where('type', RequestTypeContants::EDIT_EXAMS_SCORES)->count();
        if($checkIsExistRequest)
            return ['data'=>null, 'message' => 'Request was created before!'];
        $request = $this->model->create([
            'user_request_id' => $userId,
            'status' => RequestStatusContants::PENDING,
            'type' => RequestTypeContants::EDIT_EXAMS_SCORES,
            'content' => '{"exam_id":' . $examId.'}'
        ]);
        if ($request) {
            return ['data' => $request, 'message' => Message::createSuccessfully('request')];
        } else {
            return ['data' => $request, 'message' => Message::error()];
        }
    }

    public function reject($id)
    {
        $result =  $this->model->where('id', $id)->update(['status' => RequestStatusContants::REJECTED]);
        if ($result) {
            $request = $this->model->find($id);
            return ['data' => $request, 'message' => Message::rejectRequestSuccessfully()];
        } else {
            return ['data' => null, 'message' => Message::error()];
        }
    }

    protected function approveReviewScoreRequest($id)
    {
        $user = Auth::user();
        $result = $this->model->where('id', $id)->update(['status' => RequestStatusContants::APPROVED,'user_approve_id' => $user->id]);
        if ($result) {
            $request = $this->model->find($id);
            return ['data' => $request, 'message' => Message::approveRequestSuccessfully()];
        } else {
            return ['data' => null, 'message' => Message::error()];
        }
    }

    protected function approveSwitchCourseRequest($id)
    {
        try {
            DB::beginTransaction();
            $user =Auth::user();
            $resultRequestUpdate = $this->model->where('id', $id)->update(['status' => RequestStatusContants::APPROVED, 'user_approve_id' => $user->id]);
            $request = $this->model->find($id);
            $content = json_decode($request->content);
            $resultUpdateUserCourse = $this->userCourseModel->where('user_id', $request->user_request_id)->where('course_id', $content->old_course_id)->update(['course_id' => $content->new_course_id]);
            DB::commit();
            if ($resultRequestUpdate && $resultUpdateUserCourse) {
                return ['data' => $request, 'message' => Message::approveRequestSuccessfully()];
            } else {
                return ['data' => null, 'message' => Message::error()];
            }
        } catch (Exception $e) {
            DB::rollBack();
            return ['data' => null, 'message' => Message::error()];
        }
        {

        }
    }


    protected function approveEditExamScoresRequest($id)
    {
        $user = Auth::user();
        $request = $this->model->find($id);
        $content = json_decode($request->content);
        $this->model->where('id', $id)->update(['status' => RequestStatusContants::APPROVED, 'user_approved_id' => $user->id]);
        $result = $this->examModel->where('id', $content->exam_id)->update(['can_edit_scores' => true]);
        PreventUpdateExamScores::dispatch($content->exam_id)->delay(now()->addWeek());
        if ($result) {
            $request = $this->model->find($id);
            return ['data' => $request, 'message' => Message::approveRequestSuccessfully()];
        } else {
            return ['data' => null, 'message' => Message::error()];
        }
    }

    public function approve($id)
    {
        $request = $this->model->find($id);
        if ($request->type === RequestTypeContants::REVIEW_GRADES) {
            return $this->approveReviewScoreRequest($id);
        } elseif ($request->type === RequestTypeContants::SWITCH_COURSE) {
            return $this->approveSwitchCourseRequest($id);
        }
        elseif ($request->type === RequestTypeContants::EDIT_EXAMS_SCORES) {
            return $this->approveEditExamScoresRequest($id);
        } else {
            return ['data' => null, 'message' => Message::error()];
        }
    }

    public function getBookingRoomRequest($content)
    {
        $data = json_decode($content);
        $result = [
            'room' => $this->roomModel->find($data->room_id),
            'course' => $this->courseModel->with('subject')->find($data->course_id),
            'booking_date_start' =>  $data->booking_date_start,
            'booking_date_finish' => $data->booking_date_finish
        ];
        return $result;
    }

    public function getReviewScoreRequestContent($content)
    {
        $data = json_decode($content);
        $result = [
            'exam' => $this->examModel->find($data->exam_id)
        ];
        return $result;
    }

    public function getSwitchCourseRequest($content)
    {
        $data = json_decode($content);
        $result = [
            'oldCourse' => $this->courseModel->with('subject')->find($data->old_course_id),
            'newCourse' =>  $this->courseModel->with('subject')->find($data->new_course_id),
            'reason' => $data->reason
        ];
        return $result;
    }

    public function getEditExamScoresRequest($content)
    {
        $data = json_decode($content);
        $result = [
            'exam' => $this->examModel->find($data->exam_id)
        ];
        return $result;
    }

    public function getContent($id)
    {
        $request = $this->model->find($id);
      if ($request->type === RequestTypeContants::REVIEW_GRADES) {
            $content = $this->getReviewScoreRequestContent($request->content);
            $type = MyExamTypeConstants::getKey($content['exam']->type);
            $type = str_replace('_', ' ', $type);
            $type = strtolower($type);
            $type = ucwords($type);
            $content['exam']->type = $type;
            return $content;
        }
        elseif ($request->type === RequestTypeContants::EDIT_EXAMS_SCORES) {
            $content = $this->getReviewScoreRequestContent($request->content);
            $type = MyExamTypeConstants::getKey($content['exam']->type);
            $type = str_replace('_', ' ', $type);
            $type = strtolower($type);
            $type = ucwords($type);
            $content['exam']->type = $type;
            return $content;
        }
         elseif ($request->type === RequestTypeContants::SWITCH_COURSE) {
            return $this->getSwitchCourseRequest($request->content);
        } else {
            return null;
        }
    }

    public function getById($id)
    {
        return $this->model->with('userApprove')->with('userRequest')->find($id);
    }
}

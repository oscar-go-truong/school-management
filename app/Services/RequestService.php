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
    protected $mailService;

    protected $notificationService;
    public function __construct(UserCourse $userCourseModel, Room $roomModel, Course $courseModel, User $userModel, Exam $examModel, MailService $mailService, NotificationService $notificationService)
    {
        parent::__construct();
        $this->userCourseModel = $userCourseModel;
        $this->roomModel = $roomModel;
        $this->courseModel = $courseModel;
        $this->userModel = $userModel;
        $this->examModel = $examModel;
        $this->mailService = $mailService;
        $this->notificationService = $notificationService;
    }
    public function getModel()
    {
        return Request::class;
    }

    public function getTable($req)
    {
        $query = $this->model->with('userRequest')->with('userApprove')->status($req)->type($req);
        if (!Auth::user()->hasRole(UserRoleNameContants::ADMIN)) {
            $query = $query->where('user_request_id', Auth::user()->id);
        }
        $result = $this->orderNSearch($req, $query);
        $requests = $result['data'];
        $data = [];
        foreach ($requests as $request) {
            $type = ucwords(strtolower(str_replace('_', ' ', RequestTypeContants::getKey($request->type))));
            $data[] = [
                'id' => $request->id,
                'type' => $type,
                'userRequest' => $request->userRequest->fullname,
                'userApprove' => $request->userApprove ? $request->userApprove->fullname : '',
                'created_at' => $request->created_at,
                'status' => ucwords(strtolower(RequestStatusContants::getKey($request->status)))
            ];
        }
        $result['data'] = $data;
        return $result;
    }

    public function storeCreateNApproveSwitchClassRequest($req)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $request = $this->model->create([
            'user_request_id' => $req->user_request_id,
            'user_approve_id' => $user->id,
            'type' => RequestTypeContants::SWITCH_COURSE,
            'status' => RequestStatusContants::APPROVED,
            'content' => json_encode([
                'old_course_id' => $req->oldCourseId,
                'new_course_id' => $req->newCourseId,
                'reason' => 'Admin change course.',
            ]),
            ]);

            $this->userCourseModel
                ->where('user_id', $request->user_request_id)
                ->where('course_id', $req->oldCourseId)
                ->update(['course_id' => $req->newCourseId]);

            $this->mailService->mailStudentToChangeCourse($request);
            $this->notificationService->sendUserChangeToCourseNotification($request);
            DB::commit();

            $userCourse = $this->userCourseModel
                ->where('user_id', $request->user_request_id)
                ->where('course_id', $req->newCourseId)
                ->first();
            return [
                'data' => [
                'request' => $request,
                'userCourse' => $userCourse
                ],
                'message' => Message::updateSuccessfully('')
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return ['data' => null, 'message' => Message::error()];
        }
    }

    public function storeReviewScoreRequest($request)
    {
        $examId = $request->exam_id;
        $userId = Auth::id();

        $existingRequest = $this->model
            ->where('user_request_id', $userId)
            ->where('content->exam_id', $examId)
            ->where('type', RequestTypeContants::REVIEW_GRADES)
            ->where('status', '!=', RequestStatusContants::CANCELLED)
            ->first();

        if ($existingRequest) {
            return ['data' => null, 'message' => 'Request already exists.'];
        }

        $newRequest = $this->model->create([
            'user_request_id' => $userId,
            'status' => RequestStatusContants::PENDING,
            'type' => RequestTypeContants::REVIEW_GRADES,
            'content' => json_encode(['exam_id' => $examId])
        ]);

        if ($newRequest) {
            $this->notificationService->sendNewRequestNotification($userId, $newRequest->id);
            return ['data' => $newRequest, 'message' => Message::createSuccessfully('request')];
        }

        return ['data' => null, 'message' => Message::error()];
    }

    public function storeSwitchcCourseRequest($request)
    {
        $oldCourseId = $request->input('old_course_id');
        $newCourseId = $request->input('new_course_id');
        $reason = $request->input('reason');
        $userId = Auth::id();

        $existingRequest = $this->model
        ->where('user_request_id', $userId)
        ->where('content->old_course_id', $oldCourseId)
        ->where('type', RequestTypeContants::SWITCH_COURSE)
        ->where('status', RequestStatusContants::PENDING)
        ->first();

        if ($existingRequest) {
            return ['data' => null, 'message' => 'A request for this course switch already exists.'];
        }

        $newRequest = $this->model->create([
        'user_request_id' => $userId,
        'status' => RequestStatusContants::PENDING,
        'type' => RequestTypeContants::SWITCH_COURSE,
        'content' => json_encode([
            'old_course_id' => $oldCourseId,
            'new_course_id' => $newCourseId,
            'reason' => $reason
        ])
        ]);

        if ($newRequest) {
            $this->notificationService->sendNewRequestNotification($userId, $newRequest->id);
            return ['data' => $newRequest, 'message' => Message::createSuccessfully('request')];
        }

        return ['data' => null, 'message' => Message::error()];
    }


    public function storeEditExamScoresRequest($request)
    {
        $userId = Auth::id();
        $examId = $request->input('exam_id');

        $existingRequest = $this->model
        ->where('user_request_id', $userId)
        ->where('content->exam_id', $examId)
        ->where('status', RequestStatusContants::PENDING)
        ->where('type', RequestTypeContants::EDIT_EXAM_SCORES)
        ->first();

        if ($existingRequest) {
            return ['data' => null, 'message' => 'A request for editing this exam scores already exists.'];
        }

        $newRequest = $this->model->create([
        'user_request_id' => $userId,
        'status' => RequestStatusContants::PENDING,
        'type' => RequestTypeContants::EDIT_EXAM_SCORES,
        'content' => json_encode([
            'exam_id' => $examId
        ])
        ]);

        if ($newRequest) {
            $this->notificationService->sendNewRequestNotification($userId, $newRequest->id);
            return ['data' => $newRequest, 'message' => Message::createSuccessfully('request')];
        }

        return ['data' => null, 'message' => Message::error()];
    }

    public function reject($id)
    {
        $user = Auth::user();
        $result =  $this->model
                   ->where('id', $id)
                   ->update(['status' => RequestStatusContants::REJECTED, 'user_approve_id' => $user->id ]);

        if ($result) {
            $request = $this->model->find($id);
            $this->notificationService->sendUserHasRejectedRequestNotification($request);
            return [
                    'data' => $this->model->find($id),
                    'message' => Message::rejectRequestSuccessfully()
            ];
        }

        return [
            'data' => null,
            'message' => Message::error()
        ];
    }

    protected function approveReviewScoreRequest($id)
    {
        $user = Auth::user();
        $result = $this->model->where('id', $id)->update(['status' => RequestStatusContants::APPROVED,'user_approve_id' => $user->id]);

        if ($result) {
            $request = $this->model->find($id);
            $this->notificationService->sendUserHasApprovedRequestNotification($request);
            return [
                'data' => $this->model->find($id),
                'message' => Message::approveRequestSuccessfully()
            ];
        }

        return [
            'data' => null,
            'message' => Message::error()
        ];
    }

    protected function approveSwitchCourseRequest($id)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            $this->model
                ->where('id', $id)
                ->update([
                    'status' => RequestStatusContants::APPROVED,
                    'user_approve_id' => $user->id
                ]);

            $request = $this->model->find($id);
            $content = json_decode($request->content);

            $this->userCourseModel
                ->where('user_id', $request->user_request_id)
                ->where('course_id', $content->old_course_id)
                ->update(['course_id' => $content->new_course_id]);

            $this->mailService->mailStudentToChangeCourse($request);
            $this->notificationService->sendUserHasApprovedRequestNotification($request);
            DB::commit();

            return ['data' => $request, 'message' => Message::approveRequestSuccessfully()];
        } catch (Exception $e) {
            DB::rollBack();
            return ['data' => null, 'message' => Message::error()];
        }
    }


    protected function approveEditExamScoresRequest($id)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $request = $this->model->find($id);
            $content = json_decode($request->content);

            $this->model
                ->where('id', $id)
                ->update([
                'status' => RequestStatusContants::APPROVED,
                'user_approve_id' => $user->id
            ]);

            $this->examModel->where('id', $content->exam_id)->update(['can_edit_scores' => true]);

            PreventUpdateExamScores::dispatch($content->exam_id)->delay(now()->addWeek());

            $request = $this->model->find($id);
            $this->notificationService->sendUserHasApprovedRequestNotification($request);
            DB::commit();

            $request = $this->model->find($id);
            return ['data' => $request, 'message' => Message::approveRequestSuccessfully()];
        } catch (Exception $e) {
            DB::rollBack();
            return ['data' => null, 'message' => Message::error()];
        }
    }

    public function approve($id)
    {
        $request = $this->model->find($id);

        switch ($request->type) {
            case RequestTypeContants::REVIEW_GRADES:
                return $this->approveReviewScoreRequest($id);
            case RequestTypeContants::SWITCH_COURSE:
                return $this->approveSwitchCourseRequest($id);
            case RequestTypeContants::EDIT_EXAM_SCORES:
                return $this->approveEditExamScoresRequest($id);
            default:
                return ['data' => null, 'message' => Message::error()];
        }
    }
    public function cancel($id)
    {
        $request = $this->model->find($id);
        if (!$request) {
            return ['data' => null, 'message' => Message::error()];
        }

        $request->status = RequestStatusContants::CANCELLED;
        $result = $request->save();

        if ($result) {
            return ['data' => $request->only(['id', 'status']), 'message' => Message::cancelRequestSuccessfully()];
        } else {
            return ['data' => null, 'message' => Message::error()];
        }
    }
    public function getReviewScoreRequestContent($content)
    {
        $data = json_decode($content);
        $result = [
            'exam' => $this->examModel->find($data->exam_id)
        ];
        return $result;
    }

    public function getSwitchCourseRequestContent($content)
    {
        $data = json_decode($content);
        $result = [
            'oldCourse' => $this->courseModel->with('subject')->find($data->old_course_id),
            'newCourse' =>  $this->courseModel->with('subject')->find($data->new_course_id),
            'reason' => $data->reason
        ];
        return $result;
    }

    public function getEditExamScoresRequestContent($content)
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

        switch ($request->type) {
            case RequestTypeContants::SWITCH_COURSE:
                return $this->getSwitchCourseRequestContent($request->content);
            case RequestTypeContants::REVIEW_GRADES:
                $content = $this->getReviewScoreRequestContent($request->content);
                break;
            case RequestTypeContants::EDIT_EXAM_SCORES:
                $content = $this->getEditExamScoresRequestContent($request->content);
                break;
            default:
                return null;
        }

        $type = MyExamTypeConstants::getKey($content['exam']->type);
        $type = str_replace('_', ' ', $type);
        $type = ucwords(strtolower($type));
        $content['exam']->type = $type;

        return $content;
    }
    public function getById($id)
    {
        return $this->model->with('userApprove')->with('userRequest')->find($id);
    }

    public function getPendingRequestCount()
    {
        return ['data' => ['count' => $this->model->where('status', RequestStatusContants::PENDING)->count()],'message' => 'success'];
    }
}

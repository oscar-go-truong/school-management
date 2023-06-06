<?php

namespace App\Services;

use App\Enums\RequestTypeContants;
use App\Enums\UserRoleNameContants;
use App\Events\NewRequestEvent;
use App\Events\RequestApprovedEvent;
use App\Events\RequestRejectedEvent;
use App\Events\RequestTeacherUpdateScoreEvent;
use App\Events\UserJoinToCourseEvent;
use App\Events\UserToChangeCourseEvent;
use App\Events\UserToJoinEventEvent;
use App\Models\Course;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Notification;
use App\Models\Score;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;

class NotificationService extends BaseService
{
    protected $userModel;
    protected $courseModel;
    protected $scoreModel;
    protected $eventModel;
    protected $eventParticipantModel;

    public function __construct(User $userModel, Course $courseModel, Score $scoreModel, Event $eventModel, EventParticipant $eventParticipantModel)
    {
        parent::__construct();
        $this->userModel = $userModel;
        $this->courseModel = $courseModel;
        $this->scoreModel = $scoreModel;
        $this->eventModel = $eventModel;
        $this->eventParticipantModel = $eventParticipantModel;
    }
    public function getModel()
    {
        return Notification::class;
    }

    public function sendNewRequestNotification($userId, $requestId)
    {
        $user = $this->userModel->find($userId);
        $notification = $this->model->create([
            'user_id' => null,
             'title' => 'New request',
             'message' => $user->fullname . ' just created a new request that needs review!',
             'url' => '/requests/' . $requestId
        ]);
        event(new NewRequestEvent($notification));
    }

    public function sendEventInvatationNotification($eventId)
    {
        $event = $this->eventModel->find($eventId);
        $participants = $this->eventParticipantModel->where('event_id', $eventId)->get();
        foreach ($participants as $participant) {
            $notification = $this->model->create([
                'user_id' => $participant->participant_id,
                'title' => 'New event',
                'message' => "You has new event " . $event->name . " on " . $event->date . " at room " . $event->room->name ,
                'url' => '/schedules/'
            ]);
            event(new UserToJoinEventEvent($notification, $participant->participant_id));
        }
    }

    public function sendUserJoinToCourseNotification($userId, $courseId)
    {
        $course = $this->courseModel->find($courseId);
        $notification = $this->model->create([
            'user_id' => $userId,
            'title' => 'New Course',
            'message' => 'You just have been added to course ' . $course->subject->name . " " . $course->name ,
            'url' => '/courses/' . $courseId
        ]);
        event(new UserJoinToCourseEvent($notification, $userId));
    }

    public function sendUserChangeToCourseNotification($request)
    {
        $content =  $content = json_decode($request->content);
        $newCourse = $this->courseModel->find($content->new_course_id);
        $oldCourse = $this->courseModel->find($content->old_course_id);
        $userApporve = $this->userModel->find($request->user_approve_id);
        $notification = $this->model->create([
            'user_id' => $request->user_request_id,
            'title' => 'New Course',
            'message' => 'You just have been change from course ' . $oldCourse->name . " to course  " . $newCourse->name . ' of ' . $newCourse->subject->name . " by " . $userApporve->fullname,
            'url' => '/courses/' . $newCourse->id
        ]);
        event(new UserToChangeCourseEvent($notification, $request->user_request_id));
    }

    public function sendUserHasApprovedRequestNotification($request)
    {
        $type = ucwords(strtolower(str_replace('_', ' ', RequestTypeContants::getKey($request->type))));
        $userApporve = $this->userModel->find($request->user_approve_id);
        $notification = $this->model->create([
            'user_id' => $request->user_request_id,
            'title' => 'Request approved',
            'message' => 'Your request ' . $type . ' just have been approve by ' . $userApporve->fullname,
            'url' => '/requests/' . $request->id
        ]);
        event(new RequestApprovedEvent($notification, $request->user_request_id));
    }

    public function sendUserHasRejectedRequestNotification($request)
    {
        $type = ucwords(strtolower(str_replace('_', ' ', RequestTypeContants::getKey($request->type))));
        $userApporve = $this->userModel->find($request->user_approve_id);
        $notification = $this->model->create([
            'user_id' => $request->user_request_id,
            'title' => 'Request rejected',
            'message' => 'Your request ' . $type . ' just have been reject by ' . $userApporve->fullname,
            'url' => '/requests/' . $request->id
        ]);
        event(new RequestRejectedEvent($notification, $request->user_request_id));
    }

    public function sendRequestTeacherUpdateScoreNotification($request)
    {
        $content = json_decode($request->content);
        $score = $this->scoreModel->where('student_id', $request->user_request_id)->where('exam_id', $content->exam_id)->first();
        $userApporve = $this->userModel->find($request->user_approve_id);
        $notification = $this->model->create([
            'user_id' => $score->updated_by,
            'title' => 'Request review score',
            'message' => 'You have a request reivew score for student create by ' . $userApporve->fullname,
            'url' => '/scores/update/' . $score->edit_key
        ]);
        event(new RequestTeacherUpdateScoreEvent($notification, $score->updated_by));
    }

    public function getTable()
    {
        $user = Auth::user();
        if ($user->hasRole(UserRoleNameContants::ADMIN)) {
            return $this->model->whereRaw('(user_id is null or user_id=' . $user->id . ')')->orderBy('created_at', 'desc')->get();
        }
        return $this->model->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
    }

    public function makeAsRead($id)
    {
        $this->model->where('id', $id)->update(['read_at' => now()]);
    }
}

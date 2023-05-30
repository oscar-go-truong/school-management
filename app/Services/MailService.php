<?php

namespace App\Services;

use App\Mail\MailInvitationToEventLetter;
use App\Mail\MailStudentToChangeCourse;
use App\Mail\MailUserToJoinCourse;
use App\Mail\RequestTeacherUpdateScoreLetter;
use App\Models\Course;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Request;
use App\Models\Score;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class MailService
{
    protected $userModel;
    protected $courseModel;
    protected $scoreModel;
    protected $requestModel;
    protected $eventModel;
    protected $eventParticipantModel;

    public function __construct(User $userModel, Course $courseModel, Score $scoreModel, Request $requestModel, Event $eventModel, EventParticipant $eventParticipant)
    {
        $this->userModel = $userModel;
        $this->courseModel = $courseModel;
        $this->scoreModel = $scoreModel;
        $this->requestModel = $requestModel;
        $this->eventModel = $eventModel;
        $this->eventParticipantModel = $eventParticipant;
    }

    public function mailUserToJoinCourse($userId, $courseId)
    {
        $user = $this->userModel->find($userId);
        $course = $this->courseModel->find($courseId);
        Mail::to($user)->queue(new MailUserToJoinCourse($user, $course));
    }

    public function mailStudentToChangeCourse($request)
    {
        $content = json_decode($request->content);
        $userRequest = $this->userModel->find($request->user_request_id);
        $userApprove = $this->userModel->find($request->user_approve_id);
        $newCourse = $this->courseModel->find($content->new_course_id);
        $oldCourse = $this->courseModel->find($content->old_course_id);
        $reason = $content->reason;
        $date = date('Y-m-d H:i:s');
        Mail::to($userRequest)->queue(new MailStudentToChangeCourse($userRequest, $newCourse, $oldCourse, $userApprove, $reason, $date));
    }

    public function mailResetPassword($request)
    {
        $email = $request->email;
        Password::sendResetLink(
            ['email' => $email]
        );
    }

    public function mailInvitationToEnvent($eventId)
    {
        $event = $this->eventModel->with('owner')->with('room')->find($eventId);
        $participants = $this->eventParticipantModel->where('event_id', $eventId)->get();
        foreach ($participants as $participant) {
            $user = $this->userModel->find($participant->participant_id);
            Mail::to($user)->queue(new MailInvitationToEventLetter($user, $event));
        }
    }

    public function mailRequestTeacherUpdateScore($requestId)
    {
        $request = $this->requestModel->find($requestId);
        $content = json_decode($request->content);
        $score = $this->scoreModel->where('exam_id', $content->exam_id)->where('student_id', $request->user_request_id)->with('exam.course.subject')->with('user')->first();
        $user = $this->userModel->find($score->updated_by);
        Mail::to($user)->queue(new RequestTeacherUpdateScoreLetter($user, $score));
    }
}

<?php

namespace App\Services;

use App\Mail\MailInvitationToEventLetter;
use App\Mail\MailStudentToChangeCourse;
use App\Mail\MailUserToJoinCourse;
use App\Models\Course;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class MailService
{
    protected $userModel;
    protected $CourseModel;
    protected $eventModel;
    protected $eventParticipantModel;

    public function __construct(User $userModel, Course $courseModel, Event $eventModel, EventParticipant $eventParticipant)
    {
        $this->userModel = $userModel;
        $this->CourseModel = $courseModel;
        $this->eventModel = $eventModel;
        $this->eventParticipantModel = $eventParticipant;
    }

    public function mailUserToJoinCourse($userId, $courseId)
    {
        $user = $this->userModel->find($userId);
        $course = $this->CourseModel->find($courseId);
        Mail::to($user)->queue(new MailUserToJoinCourse($user, $course));
    }

    public function mailStudentToChangeCourse($request)
    {
        $content = json_decode($request->content);
        $userRequest = $this->userModel->find($request->user_request_id);
        $userApprove = $this->userModel->find($request->user_approve_id);
        $newCourse = $this->CourseModel->find($content->new_course_id);
        $oldCourse = $this->CourseModel->find($content->old_course_id);
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
}

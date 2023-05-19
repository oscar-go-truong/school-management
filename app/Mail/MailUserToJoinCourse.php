<?php

namespace App\Mail;

use App\Enums\UserRoleNameContants;
use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailUserToJoinCourse extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $course;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Course $course)
    {
        $this->user = $user;
        $this->course = $course;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $course = $this->course;
        if($user->hasRole(UserRoleNameContants::TEACHER))
            $viewName = 'emails.teacherToJoinTheCourse';
        else 
            $viewName = 'emails.studentToJoinTheCourse';
        return $this->view($viewName, compact('user', 'course'))->subject('[Addition to Class Notification] - '.$user->fullname);  
    }
}

<?php

namespace App\Mail;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailStudentToChangeCourse extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected $userRequest;
    protected $newCourse;
    protected $oldCourse;

    protected $userApprove;
    protected $reason;
    protected $date;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $userRequest, Course $newCourse, Course $oldCourse, User $userApprove, $reason, $date)
    {
        $this->userRequest = $userRequest;
        $this->newCourse = $newCourse;
        $this->oldCourse = $oldCourse;
        $this->userApprove = $userApprove;
        $this->reason = $reason;
        $this->date = $date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->userRequest;
        $newCourse = $this->newCourse;
        $oldCourse = $this->oldCourse;
        $userApprove = $this->userApprove;
        $reason = $this->reason;
        $date = $this->date;
        return $this->view('emails.studentToChangeCourse', compact('user', 'newCourse', 'oldCourse', 'userApprove', 'reason', 'date'))->subject('[Class Change Notification] - ' . $user->fullname);
    }
}

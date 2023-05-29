<?php

namespace App\Mail;

use App\Models\Score;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestTeacherUpdateScoreLetter extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected $user;
    protected $score;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Score $score)
    {
        $this->user = $user;
        $this->score = $score;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $score = $this->score;
        return $this->view('emails.requesTeacherUpdateScoreLetter', compact('user', 'score'))->subject('[Request review scores] - ' . $score->exam->course->subject->name . ' ' . $score->exam->course->name);
    }
}

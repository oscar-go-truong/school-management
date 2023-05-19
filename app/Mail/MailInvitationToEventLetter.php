<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailInvitationToEventLetter extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $event;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Event $event)
    {
        $this->user = $user;
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $event = $this->event;
        return $this->view('emails.invatationToEventLetter', compact('user', 'event'))->subject('[Invitation to event] - '.$event->name.' - '.$user->fullname);
    }
}

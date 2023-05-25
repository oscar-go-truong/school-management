<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestRejectedEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    protected $userId;
    public $title;
    public $message;
    public $id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Notification $notification, $userId)
    {
        $this->title = $notification->title;
        $this->message = $notification->message;
        $this->userId = $userId;
        $this->id = $notification->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['users.' . $this->userId];
    }

    public function broadcastAs()
    {
        return 'requests';
    }
}

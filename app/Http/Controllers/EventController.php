<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Services\CourseService;
use App\Services\EventService;
use App\Services\RoomService;
use App\Services\UserService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $userService;
    protected $courseService;
    protected $roomService;

    protected $eventService;

    public function __construct(UserService $userService, CourseService $courseService, RoomService $roomService,EventService $eventService)
    {
        $this->userService = $userService;
        $this->courseService = $courseService;
        $this->roomService = $roomService;
        $this->eventService = $eventService;
    }
    public function create()
    {
        $users = $this->userService->getAllActive();
        $courses = $this->courseService->getAllActive();
        return view('schedule.createEvent', compact('users','courses'));
    }

    public function store(CreateEventRequest $createEventRequest)
    {
        $input = $createEventRequest->input();
        $result = $this->eventService->store($input);
        if($result)
            return redirect('/schedules');
        else
            return redirect()->back();

    }
}

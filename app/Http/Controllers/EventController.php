<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Services\CourseService;
use App\Services\EventService;
use App\Services\UserService;

class EventController extends Controller
{
    protected $userService;
    protected $courseService;

    protected $eventService;

    public function __construct(UserService $userService, CourseService $courseService, EventService $eventService)
    {
        $this->userService = $userService;
        $this->courseService = $courseService;
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
        $result = $this->eventService->store($createEventRequest);
        if($result)
            return redirect('/schedules');
        else
            return redirect()->back();

    }
}

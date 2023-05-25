<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\UserCourse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventService extends BaseService
{
    protected $eventParticipantModel;
    protected $userCourseModel;
    protected $mailService;
    protected $notificationService;

    public function __construct(EventParticipant $eventParticipantModel, UserCourse $userCourseModel, MailService $mailService, NotificationService $notificationService)
    {
        parent::__construct();
        $this->eventParticipantModel = $eventParticipantModel;
        $this->userCourseModel = $userCourseModel;
        $this->mailService = $mailService;
        $this->notificationService = $notificationService;
    }
    public function getModel()
    {
        return Event::class;
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $input = $request->input();
            $input['created_by'] = $user->id;
            $event = $this->model->create($input);
            $users = $input['users'];
            $this->eventParticipantModel->create(['participant_id' => $user->id, 'event_id' => $event->id]);
            foreach ($users as $userId) {
                if ($this->eventParticipantModel->where('participant_id', $userId)->where('event_id', $event->id)->count() === 0) {
                    $this->eventParticipantModel->create(['participant_id' => $userId, 'event_id' => $event->id]);
                }
            }
            if (isset($input['courses'])) {
                $courses = $input['courses'];
                foreach ($courses as $courseId) {
                    $userCourses = $this->userCourseModel->where('course_id', $courseId)->get();
                    foreach ($userCourses as $userCourse) {
                        if ($this->eventParticipantModel->where('participant_id', $userCourse->user_id)->where('event_id', $event->id)->count() === 0) {
                            $this->eventParticipantModel->create(['participant_id' => $userCourse->user_id, 'event_id' => $event->id]);
                        }
                    }
                }
            }
            $this->notificationService->sendEventInvatationNotification($event->id);
            $this->mailService->mailInvitationToEnvent($event->id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function getEvents()
    {
        $user = Auth::user();
        $eventRaws = $this->model->whereHas('eventParticipants', function ($query) use ($user) {
            $query->where('participant_id', $user->id);
        })->with('room')->get();
        $events = [];
        foreach ($eventRaws as $eventRaw) {
            $events[] = [
                'id' => $eventRaw->id ,
                'title' => $eventRaw->name . ' at room ' . $eventRaw->room->name,
                'start' => $eventRaw->date . ' ' . $eventRaw->start_time,
                'end' => $eventRaw->date . ' ' . $eventRaw->end_time,
                'description' =>  $eventRaw->name . ' at room ' . $eventRaw->room->name,
                'color' => 'green',
                'category' => 'event'
            ];
        }
        return $events;
    }

    public function checkConflictTime($request)
    {
        $user = Auth::user();
        $date = $request->date;
        $startTime = $request->start_time;
        $endTime = $request->end_time;
        $event =  $this->model->whereRaw('((start_time <"' . $startTime . '" and "' . $startTime . '"< end_time) or (start_time <= "' . $endTime . '" and "' . $endTime . '" <= end_time) or (start_time >= "' . $startTime . '" and "' . $endTime . '" >=end_time))')->where('date', $date)->with('room')->whereHas('eventParticipants', function ($query) use ($user) {
            $query->where('participant_id', $user->id);
        })->first();
        if ($event) {
            return $event->name . ' from ' . $event->start_time . ' to ' . $event->end_time . ', ' . $event->date . ' at ' . $event->room->name;
        }
    }
}

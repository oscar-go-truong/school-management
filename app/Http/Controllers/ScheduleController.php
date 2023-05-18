<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
   protected $scheduleService;

   protected $eventService;


   public function __construct(ScheduleService $scheduleService,EventService $eventService)
   {
      $this->scheduleService = $scheduleService;
      $this->eventService = $eventService;
   }
   public function index()
   {
    return view('schedule.index');
   }

   public function getTable(Request $request)
   {
      $schedules = $this->scheduleService->getSchedules();
      $events = $this->eventService->getEvents();
      return response()->json(['data'=>['schedules' => $schedules, 'events' => $events]]);
   }

   public function checkIsConflictTime(Request $request)
   {
      $schedule = $this->scheduleService->checkConflictTime($request);
      $event = $this->eventService->checkConflictTime($request);
      return response()->json(['data'=>['schedule' => $schedule, 'event' => $event]]);
   }
}

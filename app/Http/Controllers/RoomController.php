<?php

namespace App\Http\Controllers;

use App\Services\RoomService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    protected $roomService;

    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }

    public function getAvailableRoomForEvent(Request $request)
    {
        $rooms = $this->roomService->getAvailableRoomForEvent($request);
        return $rooms;
    }

    public function getAvailableRoomForSchedule(Request $request)
    {
        $rooms = $this->roomService->getAvailableRoomForSchedule($request);
        return $rooms;
    }
}

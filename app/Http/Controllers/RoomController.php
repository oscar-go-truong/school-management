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

    public function getAvailable(Request $request)
    {
        $rooms = $this->roomService->getAvailable($request);
        return $rooms;
    }
}

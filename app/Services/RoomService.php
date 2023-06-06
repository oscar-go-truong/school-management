<?php

namespace App\Services;

use App\Enums\StatusTypeContants;
use App\Models\Room;
use Carbon\Carbon;

class RoomService extends BaseService
{
    public function getModel()
    {
        return Room::class;
    }

    public function getAvailableRoomForEvent($request)
    {
        $date = $request->date;
        $startTime = $request->start_time;
        $endTime = $request->end_time;
        $rooms = $this->model->select('id', 'name')->where('status', StatusTypeContants::ACTIVE)->whereDoesntHave('events', function ($query) use ($date, $startTime, $endTime) {
            $query->whereRaw('((start_time <"' . $startTime . '" and "' . $startTime . '"< end_time) or (start_time <= "' . $endTime . '" and "' . $endTime . '" <= end_time) or (start_time >= "' . $startTime . '" and "' . $endTime . '" >=end_time))')->where('date', $date);
        })->whereDoesntHave('schedules', function ($query) use ($date, $startTime, $endTime) {
            $weekday = Carbon::parse($date)->format('D');
            $query->whereHas('course', function ($query) {
                $query->where('status', StatusTypeContants::ACTIVE);
            })->whereRaw('((start_time <="' . $startTime . '" and "' . $startTime . '"<= finish_time) or (start_time <= "' . $endTime . '" and "' . $endTime . '" <= finish_time) or (start_time >= "' . $startTime . '" and "' . $endTime . '" >= finish_time))')->where('weekday', $weekday);
        })->orderBy('name', 'asc')->get();
        return ['data' => ['rooms' => $rooms]];
    }

    public function getAvailableRoomForSchedule($request)
    {
        $startTime = $request->start_time;
        $finishTime = $request->finish_time;
        $weekday = $request->weekday;
        $rooms = $this->model->select('id', 'name')->whereDoesntHave('schedules', function ($query) use ($startTime, $finishTime, $weekday) {
            $query->whereHas('course', function ($query) {
                $query->where('status', StatusTypeContants::ACTIVE);
            })->whereRaw('((start_time <="' . $startTime . '" and "' . $startTime . '"<= finish_time) or (start_time <= "' . $finishTime . '" and "' . $finishTime . '" <= finish_time) or (start_time >= "' . $startTime . '" and "' . $finishTime . '" >= finish_time))')->where('weekday', $weekday);
        })->orderBy('name', 'asc')->get();
        return ['data' => ['rooms' => $rooms]];
    }
}

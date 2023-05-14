<?php
namespace App\Services;
use App\Enums\StatusTypeContants;
use App\Models\Room;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class RoomService extends BaseService {

    public function getModel()
    {
        return Room::class;
    }

    public function getAvailable($input)
    {
        $date =  $input['date'];
        $startTime = $input['start_time'];
        $endTime = $input['end_time'];
        $rooms = $this->model->where('status',StatusTypeContants::ACTIVE)->whereDoesntHave('events', function($query) use($date, $startTime, $endTime){
            $query->whereRaw('((start_time <"'.$startTime.'" and "'.$startTime.'"< end_time) or (start_time <= "'.$endTime.'" and "'.$endTime.'" <= end_time) or (start_time >= "'.$startTime.'" and "'.$endTime.'" >=end_time))')->where('date',$date);
        })->whereDoesntHave('schedules', function($query) use($date, $startTime, $endTime){
            $weekday = Carbon::parse($date)->format('D') ;
            $query->whereHas('course',function($query){
                $query->where('status', StatusTypeContants::ACTIVE);
            })->whereRaw('((start_time <="'.$startTime.'" and "'.$startTime.'"<= finish_time) or (start_time <= "'.$endTime.'" and "'.$endTime.'" <= finish_time) or (start_time >= "'.$startTime.'" and "'.$endTime.'" >= finish_time))')->where('weekday',$weekday); 
        })->get();
        return ['data' => ['rooms' => $rooms], 'message' => 'OK'];
    }
}
<?php
namespace App\Services;
use App\Enums\StatusTypeContants;
use App\Models\Schedule;
use App\Models\UserCourse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ScheduleService extends BaseService
{
    protected $userCourseModel;

    public function __construct(UserCourse $userCourseModel)
    {
        parent::__construct();
        $this->userCourseModel = $userCourseModel;
    }
    public function getModel()
    {
        return Schedule::class;
    }

    public function getSchedules()
    {
        $daysOfWeek = ["Sun" => 0, "Mon" => 1, "Tue" => 2, "Wed" => 3, "Thu" => 4, "Fri" => 5, "Sat" => 6 ];
        $user = Auth::user();
        $userCourses = $this->userCourseModel->where('user_id',$user->id)->whereHas('course', function($query){
            $query->where('status', StatusTypeContants::ACTIVE);
        })->get()->load('course.subject', 'course.schedules.room');
        $schedules = [];
        foreach($userCourses as $userCourse) 
        {
            foreach($userCourse->course->schedules as $schedule)
                $schedules[] = [
                    'id' => $schedule->id, 
                    'startTime' => $schedule->start_time, 
                    'endTime' => $schedule->finish_time, 
                    'title' => $userCourse->course->subject->name." - ".$userCourse->course->name.' at room '.$schedule->room->name, 
                    'daysOfWeek' => [$daysOfWeek[$schedule->weekday]], 
                    'description' => $userCourse->course->subject->name." - ".$userCourse->course->name.' at room '.$schedule->room->name,
                    'category' => 'schedule'
                ];
        }
        return $schedules;
    } 

    public function checkConflictTime($request)
    {
        $user = Auth::user();
        if($user->hasRole('admin'))
            return null;
        $date = $request->date;
        $startTime = $request->start_time;
        $endTime = $request->end_time;
        $weekday =  $weekday = Carbon::parse($date)->format('D');

        $schedule = $this->model->whereHas('course.userCourses', function($query) use($user){
            $query->where('user_id', $user->id);
        })->whereRaw('((start_time <="'.$startTime.'" and "'.$startTime.'"<= finish_time) or (start_time <= "'.$endTime.'" and "'.$endTime.'" <= finish_time) or (start_time >= "'.$startTime.'" and "'.$endTime.'" >= finish_time))')->where('weekday',$weekday)->with('course.subject')->with('room')->first();
        if($schedule)
            return $schedule->course->subject->name.' '.$schedule->course->name.' from '.$schedule->start_time.' to '.$schedule->finish_time.', '.$schedule->weekday.', '.$date.' at '.$schedule->room->name;
        return null;
    }
}
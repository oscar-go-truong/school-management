<?php

namespace App\Services;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleNameContants;
use App\Helpers\Message;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\User;
use App\Models\UserCourse;

class UserCourseService extends BaseService
{
    public function getModel()
    {
        return UserCourse::class;
    }

    public function getTable($request, $courseId, $role)
    {
        $query = $this->model->where('course_id', $courseId)->whereHas('user', function ($query) use ($role) {
            $query->role($role);
        })->with('user');
        $result = $this->orderNSearch($request, $query);
        $userCourses = $result['data'];
        $data = [];
        foreach($userCourses as $userCourse)
        {
            $data[] = [
                'id' => $userCourse->id,
                'user_id' => $userCourse->user_id,
                'fullname' => $userCourse->user->fullname,
                'email' => $userCourse->user->email,
                'joined_at' => $userCourse->created_at
            ];
        }
        $result['data'] = $data;
        return $result;
    }

    public function getUsersByRole($courseId, $role)
    {
        $data = $this->model->where('course_id', $courseId)->whereHas('user', function ($query) use ($role) {
            $query->role($role);
        })->with('user')->get();
        return $data;
    }
    public function checkUserWasJoinedCourse($user_id, $course_id) 
    {
        return $this->model->where('user_id',$user_id)->where('course_id',$course_id)->count();
    }

    protected function checkUSerIsJoinedSubjectInThisYear($user, $course)
    {
       return  $this->model->whereHas('course', function($query) use($course){
            $query->where('subject_id', $course->subject_id)->whereRaw('YEAR(created_at) = '.$course->created_at->year);
        })->where('user_id',$user->id)->with('course.subject')->first();
    }

    protected function checkConflictScheduleWithOtherCourse($user, $course)
    {
        $userCourses = $this->model->where('user_id',$user->id)->where('status', StatusTypeContants::ACTIVE)->whereHas('course', function($query){
            $query->where('status', StatusTypeContants::ACTIVE);
        })->get();
        $courseSchedules = Schedule::where('course_id', $course->id)->get();
        foreach($userCourses as $userCourse)
        {
            $schedules = Schedule::where('course_id', $userCourse->course_id)->get();
            foreach($schedules as $schedule)
            {
                foreach($courseSchedules as $courseSchedule)
                {
                    if((($schedule->start_time <= $courseSchedule->start_time && $schedule->finish_time >= $courseSchedule->start_time)||($schedule->start_time <= $courseSchedule->finish_time && $schedule->finish_time >= $courseSchedule->finish_time) || ($schedule->start_time <= $courseSchedule->start_time && $schedule->finish_time >= $courseSchedule->finish_time)) && $schedule->weekday === $courseSchedule->weekday)
                        return $this->model->with('course.subject')->find($userCourse->id);
                }
            }
        }
        return null;
    }
   
    public function store($request) 
    {
        $course = Course::find($request->course_id);
        $user = User::find($request->user_id);
        
        $isConflictTime = $this->checkConflictScheduleWithOtherCourse($user, $course);
        if($isConflictTime)
            return ['data' => null, 'message' => Message::conflictTimeWithCourse($isConflictTime)];
        else
        {
            if($user->hasRole(UserRoleNameContants::STUDENT))
            {
                $isJoinedSubjectInThisYear = $this->checkUSerIsJoinedSubjectInThisYear($user, $course);
                if($isJoinedSubjectInThisYear)
                    return ['data'=>null,'wait'=>true, 'message' => Message::userWasJoinedSubjectInThisYear($user, $isJoinedSubjectInThisYear, $course->id)];
            }
            $userCourse = $this->model->updateOrCreate(['user_id' => $request->user_id, 'course_id' => $request->course_id], ['deleted_at' => null]);
            if ($userCourse) 
            return ['data' => ['id' => $this->model->where('user_id', $request->user_id)->where('course_id', $request->course_id)->first()->id], 'message' => Message::createSuccessfully("")];
                return  ['data' => null, 'message' => Message::error()];
        }
    }
}


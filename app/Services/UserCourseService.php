<?php

namespace App\Services;

use App\Enums\UserRoleContants;
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
            $query->where('role', $role);
        })->with('user');
        $teachers = $this->orderNSearch($request, $query);
        return $teachers;
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
        $userCourses = $this->model->where('user_id',$user->id)->whereRaw('YEAR(created_at) = '.$course->created_at->year)->get();
        $courseSchedules = Schedule::where('course_id', $course->id)->get();
        foreach($userCourses as $userCourse)
        {
            $schedules = Schedule::where('course_id', $userCourse->course_id)->get();
            foreach($schedules as $schedule)
            {
                foreach($courseSchedules as $courseSchedule)
                {
                    if(($schedule->start_time <= $courseSchedule->start_time && $schedule->finish_time >= $courseSchedule->start_time)||($schedule->start_time <= $courseSchedule->finish_time && $schedule->finish_time >= $courseSchedule->finish_time))
                        return $this->model->with('course.subject')->find($userCourse->id);
                }
            }
        }
        return null;
    }
   
    public function store($input) 
    {
        $course = Course::find($input['course_id']);
        $user = User::find($input['user_id']);
        
        $isConflictTime = $this->checkConflictScheduleWithOtherCourse($user, $course);
        if($isConflictTime)
            return ['data'=>null, 'message' => 'User conflict time with course <b>'.$isConflictTime->course->subject->name.' - '.$isConflictTime->course->name.'</b>!'];
        else
        {
            if($user->role === UserRoleContants::STUDENT)
            {
                $isJoinedSubjectInThisYear = $this->checkUSerIsJoinedSubjectInThisYear($user, $course);
                if($isJoinedSubjectInThisYear)
                    return ['data'=>null,'wait'=>true, 'message' => 'User has joined class <b>'.$isJoinedSubjectInThisYear->course->name.'</b> of subject <b>'.$isJoinedSubjectInThisYear->course->subject->name.'</b>. Do you want to change the class to <b>'.$course->name.'</b>?<div class="d-flex justify-content-center">
                     <button class="btn btn-secondary mr-3">No</button> 
                     <button class="btn btn-primary ml-3" data-id="'.$isJoinedSubjectInThisYear->id.'" data-newCourseId="'.$course->id.'" data-oldCourseId="'.$isJoinedSubjectInThisYear->course_id.'" data-userId="'.$user->id.'" id="changeCourse">Yes</button></div>
                    </div>'];
            }
            return parent::store($input);
        }
    }

}


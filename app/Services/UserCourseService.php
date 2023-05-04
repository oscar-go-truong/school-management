<?php

namespace App\Services;

use App\Enums\UserRoleContants;
use App\Models\Course;
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

    public function store($input) 
    {
        $course = Course::find($input['course_id']);
        $user = User::find($input['user_id']);
        if($user->role === UserRoleContants::STUDENT)
        {
            $isJoinedSubjectInThisYear = $this->model->whereHas('course', function($query) use($course){
                $query->where('subject_id', $course->subject_id)->whereRaw('YEAR(created_at) = '.$course->created_at->year);
            })->where('user_id',$input['user_id'])->with('course.subject')->first();
            if($isJoinedSubjectInThisYear)
                return ['data'=>null,'wait'=>true, 'message' => 'User has joined class <b>'.$isJoinedSubjectInThisYear->course->name.'</b> of subject <b>'.$isJoinedSubjectInThisYear->course->subject->name.'</b>. Do you want to change the class to <b>'.$course->name.'</b>?<div class="d-flex justify-content-center">
                     <button class="btn btn-secondary mr-3">No</button> 
                     <button class="btn btn-primary ml-3" data-id="'.$isJoinedSubjectInThisYear->id.'" data-newCourseId="'.$course->id.'" data-userId="'.$user->id.'" id="changeCourse">Yes</button></div>
                </div>'];
        }
        $isConflictTime = $this->model->whereHas('course', function ($query) use($course){
            $query->where('id','!=',$course->id)->where('weekday','=', $course->weekday)->whereRaw("((start_time <='".$course->start_time."'and "."finish_time >='".$course->start_time."') or ("."start_time <='".$course->finish_time."' and "."finish_time >='".$course->finish_time."')) and YEAR(created_at) = ".$course->created_at->year);
        })->where('user_id',$input['user_id'])->with('course.subject')->first();
        if($isConflictTime)
            return ['data'=>null, 'message' => 'User conflict time with course <b>'.$isConflictTime->course->subject->name.' - '.$isConflictTime->course->name.'</b>!'];
        else
            return parent::store($input);
    }

}


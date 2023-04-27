<?php

namespace App\Services;

use App\Enums\UserRoleContants;
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
}


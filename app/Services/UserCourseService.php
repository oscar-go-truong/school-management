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

    public function getTeachers($request, $CourseId)
    {
        $query = $this->model->where('role', UserRoleContants::TEACHER)->where('course_id', $CourseId)->with('user');
        $teachers = $this->orderNSearch($request, $query);
        return $teachers;
    }


    public function getStudents($request, $CourseId)
    {
        $query = $this->model->where('role', UserRoleContants::STUDENT)->where('course_id', $CourseId)->with('user');
        $students = $this->orderNSearch($request, $query);
        return $students;
    }
}

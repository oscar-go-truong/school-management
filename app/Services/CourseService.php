<?php

namespace App\Services;

use App\Models\Course;

class CourseService extends BaseService
{
    public function getModel()
    {
        return Course::class;
    }

    public function getTable()
    {
        $courses = $this->model->with('homeroomTeacher')->with('subject')->get();
        return $courses;
    }
}

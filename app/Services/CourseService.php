<?php

namespace App\Services;

use App\Enums\PaginationContants;
use App\Models\Course;

class CourseService extends BaseService
{
    public function getModel()
    {
        return Course::class;
    }

    public function getTable($request)
    {
        $query = $this->model->with('homeroomTeacher')->withCount('exam')->withCount('teachers')->withCount('students')->with('subject');
        $courses = $this->orderNSearch($request, $query);
        return $courses;
    }

    public function getCoursesBySubjectId($request, $id)
    {
        $query = $this->model->where('subject_id', $id)->with('homeroomTeacher')->withCount('exam')->withCount('teachers')->withCount('students')->with('subject');
        $courses = $this->orderNSearch($request, $query);
        return $courses;
    }
}

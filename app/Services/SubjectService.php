<?php

namespace App\Services;

use App\Models\Subject;

class SubjectService extends BaseService
{
    public function getModel()
    {
        return Subject::class;
    }

    public function getTable($request)
    {
        $query = $this->model->withCount('course');
        $subjects = $this->orderNSearch($request, $query);
        return $subjects;
    }
}

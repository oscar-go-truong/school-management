<?php

namespace App\Services;

use App\Enums\ExamTypeConstants;
use App\Models\Exam;

class ExamService extends BaseService
{
    public function getModel()
    {
        return Exam::class;
    }

    public function getTable($request)
    {
        
        $query = $this->model->withCount('score');
        $courseId = $request->query('courseId');
        if($courseId != null)
            $query = $query->where('course_id', $courseId);
        $exams = $this->orderNSearch($request, $query);
        foreach ($exams as $exam) {
            $exam->type = ExamTypeConstants::getKey($exam->type);
        }
        return $exams;
    }
}

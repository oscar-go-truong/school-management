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

    public function getCourseExams($request, $courseId)
    {
        $query = $this->model->where('course_id', $courseId)->withCount('score');
        $exams = $this->orderNSearch($request, $query);
        foreach ($exams as $exam) {
            $exam->type = ExamTypeConstants::getKey($exam->type);
        }
        return $exams;
    }
}

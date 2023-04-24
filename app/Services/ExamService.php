<?php

namespace App\Services;

use App\Enums\ExamType;
use App\Models\Exam;

class ExamService extends BaseService
{
    public function getModel()
    {
        return Exam::class;
    }

    public function getCourseExams($request, $courseId)
    {
        $query = $this->model->where('course_id', $courseId);
        $exams = $this->orderNSearch($request, $query);
        foreach ($exams as $exam) {
            $exam->type = ExamType::getKey($exam->type);
        }
        return $exams;
    }
}

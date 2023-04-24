<?php

namespace App\Services;

use App\Enums\MyExamTypeConstants;
use App\Models\Score;

class ScoreService extends BaseService {

    public function getModel()
    {
        return Score::class;
    }

    public function getTable($request)
    {
        $examId = $request->query('examId');
        $query = $this->model->with('exam.course.subject')->with('user');
        if($examId!=null)
            $query=$query->where('exam_id',$examId);
        $scores = $this->orderNSearch($request,$query);
        foreach($scores as $score)
        {
            $score->exam->type = MyExamTypeConstants::getKey( $score->exam->type);
        }
        return $scores;
    }
}
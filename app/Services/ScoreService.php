<?php

namespace App\Services;

use App\Enums\MyExamTypeConstants;
use App\Models\Score;
use Illuminate\Support\Facades\Auth;

class ScoreService extends BaseService {

    public function getModel()
    {
        return Score::class;
    }

    public function getTable($request)
    {
        $user = Auth::user();
        $examId = $request->query('examId');
        $query = $this->model->with('exam.course.subject')->with('user');
        if($examId!=null)
            $query=$query->where('exam_id',$examId);
        $scores = $this->orderNSearch($request,$query);
        if(!$user->isAdministrator()){
            $query = $query->whereHas('exam.course.userCourse', function ($query) use($user){
                $query->where('user_id', $user->id);
            });
        }
        foreach($scores as $score)
        {
            $score->exam->type = MyExamTypeConstants::getKey( $score->exam->type);
        }
        return $scores;
    }
}
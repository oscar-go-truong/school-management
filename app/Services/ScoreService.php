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

    public function getTable($input, $examId)
    {
        $user = Auth::user();
        $query = $this->model->with('user');
        if($examId!=null)
            $query=$query->where('exam_id',$examId);
        $scores = $this->orderNSearch($input,$query);
        if(!$user->isAdministrator()){
            $query = $query->whereHas('exam.course.userCourse', function ($query) use($user){
                $query->where('user_id', $user->id);
            });
        }
        return $scores;
    }
}
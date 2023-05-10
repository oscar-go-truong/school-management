<?php

namespace App\Services;

use App\Enums\MyExamTypeConstants;
use App\Models\Score;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function importScores($examId, $file)
    {
        try{
        $path = $file->store('csv');
        $data = array_map('str_getcsv', file(storage_path("app/$path")));
        $columns = array_shift($data);
        if(in_array('#', $columns) && in_array('Score', $columns)){
            $userIdIndex = array_search('#', $columns);
            $scoreIndex = array_search('Score', $columns);
            DB::beginTransaction();
            foreach($data as $row){
                $this->model->where('exam_id', $examId)->where('student_id', $row[$userIdIndex])->update(['total'=>$row[$scoreIndex]?$row[$scoreIndex]:null]);
            }
            DB::commit();
            return ['data'=> ['exam_id'=>$examId, 'status'=>'success'], 'message'=>'File import successful!'];
        } else {
            return ['data'=> null, 'message'=>'File import is invalid format!'];
        }
    } catch(Exception $e) 
    {
        DB::rollBack();
        return ['data'=> null, 'message'=>$e->getMessage()];
    }
    }
}
<?php

namespace App\Services;

use App\Enums\UserRoleNameContants;
use App\Helpers\Message;
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
        if(!$user->hasRole(UserRoleNameContants::ADMIN)){
            $query = $query->whereHas('exam.course.userCourse', function ($query) use($user){
                $query->where('user_id', $user->id);
            });
        }
        return $scores;
    }
    public function importScores($examId, $input)
    {
        try{
            $user = Auth::user();
            $data = $input['formData'];
            DB::beginTransaction();
            foreach($data as $row){
                if($row['score'])
                    $this->model->where('exam_id', $examId)->where('student_id', $row['user_id'])->update(['total' => $row['score'], "updated_by" => $user->id]);
            }
            DB::commit();
            return ['data' => ['exam_id' => $examId, 'status' => 'success'], 'message' => Message::importFileSuccessfully()];
    } catch(Exception $e) 
    {
        DB::rollBack();
        return ['data' => null, 'message' => Message::error()];
    }
    }

    public function detachFile($examId, $input)
    {
        try{
        $fileContent = $input['fileContent'];
        $validContent = [];
        foreach($fileContent as $content) {
            if($this->model->where('student_id', $content['user_id'])->where('exam_id', $examId)->count())
                array_push($validContent, $content);
        }
        if(count($validContent))
            return ['data' => $validContent];
        else 
            return ['data' => null, 'message' => Message::fileUploadIsInvalid()];
    }
     catch(Exception $e)  
    {
        return ['data' => null, 'message' => Message::fileUploadIsInvalid()];
    }
}

    public function getMissingUser($input)
    {
        $examId = $input['exam_id'];
        $userIdList = $input['user_id_list'];
        if($userIdList !== null)
            $users = $this->model->whereNotIn('student_id',$userIdList)->where('exam_id', $examId)->with('user')->get();
        else 
            $users = $this->model->where('exam_id', $examId)->with('user')->get();
        return ['data' => $users];
    }
}
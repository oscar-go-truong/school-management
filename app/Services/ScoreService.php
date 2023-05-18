<?php

namespace App\Services;

use App\Enums\UserRoleNameContants;
use App\Helpers\Message;
use App\Models\Exam;
use App\Models\Score;
use App\Models\UserCourse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScoreService extends BaseService {

    protected $examModel;
    protected $userCourseModel;
    public function __construct(Exam $examModel, UserCourse $userCourseModel)
    {
        parent::__construct();
        $this->examModel = $examModel;
        $this->userCourseModel = $userCourseModel;
    }
    public function getModel()
    {
        return Score::class;
    }

    public function getTable($request, $examId)
    {
        $query = $this->model->where('exam_id',$examId)->with('user');
        $result= $this->orderNSearch($request,$query);
        $scores = $result['data'];
        $data  = [];
        foreach($scores as $score)
        {
            $data[] = [
                'id' => $score->id,
                'fullname' => $score->user->fullname,
                'email' => $score->user->email,
                'total' => $score->total
            ];
        }
        $result['data'] = $data;
        return $result;
    }
    public function importScores($examId, $request)
    {
        try{
            $user = Auth::user();
            $data = $request->formData;
            DB::beginTransaction();
            foreach($data as $row){
                $this->model->updateOrCreate(['exam_id' => $examId,'student_id' => $row['user_id']],['total' => $row['total']?$row['total']:0, "updated_by" => $user->id]);
            }
            DB::commit();
            return ['data' => ['exam_id' => $examId, 'status' => 'success'], 'message' => Message::importFileSuccessfully()];
    } catch(Exception $e) 
    {
        DB::rollBack();
        return ['data' => null, 'message' => Message::error()];
    }
    }

    public function detachFile($examId, $request)
    {
        try{
            $fileContent = $request->fileContent;
            $validContent = [];
            $exam = $this->examModel->find($examId);
            foreach($fileContent as $content) {
                $userCourse = $this->userCourseModel->where('user_id', $content['user_id'])->where('course_id', $exam->course_id)->whereHas('user', function($query){
                    $query->whereHas('roles',function($query){
                        $query->where('name', UserRoleNameContants::STUDENT);
                    });
                })->with('user')->first();
                if($userCourse)
                    $validContent[] = [
                        'user_id' => $userCourse->user->id,
                        'fullname' => $userCourse->user->fullname,
                        'email' => $userCourse->user->email,
                        'total' => $content['score']
                    ];
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

    public function getMissingUser($request)
    {
        $examId = $request->exam_id;
        $userIdList = $request->user_id_list;
        $exam = $this->examModel->find($examId);
        $data = [];
        $query = $this->userCourseModel->where('course_id', $exam->course_id)->whereHas('user', function($query){
                $query->whereHas('roles',function($query){
                    $query->where('name', UserRoleNameContants::STUDENT);
                });
            })->with('user');
        if($userIdList != null)
        {
            $query->whereNotIn('user_id', $userIdList);
        }
        $userCourses = $query->get();
        foreach($userCourses as $userCourse)
        {
            $score = $this->model->where('student_id', $userCourse->user->id)->where('exam_id', $exam->id)->first();
            $data[] = [
                'user_id' => $userCourse->user->id,
                'fullname' => $userCourse->user->fullname,
                'email' => $userCourse->user->email,
                'total' => $score ? $score->total : ""
         ]; 
        }
        return ['data' => $data];
    }
}
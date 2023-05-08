<?php

namespace App\Services;

use App\Enums\MyExamTypeConstants;
use App\Enums\RequestTypeContants;
use App\Models\BookingRoomRequest;
use App\Models\Request;
use App\Models\ReviewScoreRequest;
use App\Models\SwitchCourseRequest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestService extends BaseService {

    protected $switchCourseRequestModel;
    protected $bookingRoomRequestModel;
    protected $reviewScoreRequestModel;

    public function __construct(SwitchCourseRequest $switchCourseRequest, BookingRoomRequest $bookingRoomRequest, ReviewScoreRequest $reviewScoreRequest)
    {
        parent::__construct();
        $this->switchCourseRequestModel = $switchCourseRequest;
        $this->bookingRoomRequestModel = $bookingRoomRequest;
        $this->reviewScoreRequestModel = $reviewScoreRequest;
    }
    public function getModel(){
        return Request::class;
    }

    public function getTable($input){
        $query = $this->model->with('userRequest')->with('userApprove')->status($input);
        if(!Auth::user()->isAdministrator())
            $query = $query->where('user_request_id',Auth::user()->id );
        $requests = $this->orderNSearch($input, $query);
        foreach($requests as $request){
            $type = RequestTypeContants::getKey($request->type);
            $type = str_replace('_', ' ', $type);
            $type = strtolower($type);
            $type = ucwords($type);
            $request->type = $type;
        }

        return $requests;
    }

    public function storeSwitchClassRequest($arg)
    {
        try{
            DB::beginTransaction();
            $content = $this->switchCourseRequestModel->create([
                "old_course_id" => $arg['old_course_id'],
                "new_course_id" => $arg['new_course_id']     
            ]);
            $request = $this->model->create([
                "user_request_id" => $arg['user_request_id'],
                "user_approve_id" => $arg['user_approve_id'],
                "type" => $arg['type'],
                "status" => $arg['status'],
                "content_id" => $content->id
                ]);
            DB::commit();
            return ['data'=> $request];
        }
        catch(Exception $e)
        {
            DB::rollBack();
            return ['data' => null];
        }
    }

    public function getContent($request)
    {
        if($request->type === RequestTypeContants::BOOK_ROOM_OR_LAB)
            return $this->bookingRoomRequestModel->with('room')->with('course')->find($request->content_id);
        else if($request->type === RequestTypeContants::REVIEW_GRADES)
           {
            $content = $this->reviewScoreRequestModel->with('exam.course.subject')->find($request->content_id);
            $type = MyExamTypeConstants::getKey($content->exam->type);
            $type = str_replace('_', ' ', $type);
            $type = strtolower($type);
            $type = ucwords($type);
            $content->exam->type = $type;
            return $content;
           }
        else if($request->type === RequestTypeContants::SWITCH_COURSE) 
            return $this->switchCourseRequestModel->with('newCourse.subject')->with('oldCourse.subject')->find($request->content_id);
        else 
            return null;
    }

    public function getById($id) 
    {
        return $this->model->with('userApprove')->with('userRequest')->find($id);
    }
}
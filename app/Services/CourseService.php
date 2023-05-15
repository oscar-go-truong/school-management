<?php

namespace App\Services;


use App\Enums\RequestStatusContants;
use App\Enums\RequestTypeContants;
use App\Enums\StatusTypeContants;
use App\Enums\TimeConstants;
use App\Enums\UserRoleNameContants;
use App\Helpers\Message;
use App\Models\Course;
use App\Models\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseService extends BaseService
{
    protected $userCourseService;
    protected $requestModel;

    public function __construct(UserCourseService $userCourseService, Request $requestModel)
    {
        parent::__construct();
        $this->userCourseService = $userCourseService;
        $this->requestModel = $requestModel;

    }
    public function getModel()
    {
        return Course::class;
    }

    public function getTable($input)
    {
        $subjectId = isset($input['subjectId'])?$input['subjectId']:null;
        $query = $this->model->year($input)->with('homeroomTeacher')->withCount('exam')->withCount('teachers')->withCount('students')->with('subject')->with('schedules', function($query){
            $query->orderByRaw("FIELD(weekday, '".implode("', '", TimeConstants::WEEKDAY)."')");
        });
        if($subjectId != null)
            $query = $query->where('subject_id', $subjectId);
        if(!Auth::user()->hasRole(UserRoleNameContants::ADMIN))
         $query = $query->whereHas('userCourse', function($query) {
            $query->where('user_id', Auth::user()->id);
         });
        $courses = $this->orderNSearch($input, $query);
        return $courses;
    }

    public function store($arg){
        try{
            DB::beginTransaction();
            $course =  $this->model->create($arg);
            $this->userCourseService->store([
                    'user_id' => $course->owner_id,
                    'course_id' => $course->id,
                    'status' => StatusTypeContants::ACTIVE]);
            DB::commit();
            return ['data'=> $course, 'message' => "Create successful!"];
        } catch(Exception $e) {
            DB::rollBack();
            return ['data' => null, 'message' => Message::error()];
        }
    }

    public function coursesAvailableSwicth($courseId, $userId)
    {
        $course = $this->model->find($courseId);
        return $this->model->where('id', '!=', $courseId)->where('subject_id', $course->subject_id)->where('status', StatusTypeContants::ACTIVE)->whereDoesntHave('userCourse', function($query) use($userId)
        {
                $query->where('user_id', $userId);
        })->get();
    }

    public function update($id, $arg){
        try{
            DB::beginTransaction();
            $this->model->where('id', $id)->update($arg);
            $course = $this->model->find($id);
            $teacherWasJoinedCourse = $this->userCourseService->checkUserWasJoinedCourse($course->owner_id,$course->id);
            if(!$teacherWasJoinedCourse)
                $this->userCourseService->store([
                    'user_id' => $course->owner_id,
                    'course_id' => $course->id,
                    'status' => StatusTypeContants::ACTIVE
                ]);
            DB::commit();
            return ['data' => $course, 'message' => "Update successful!"];
        }catch(Exception $e){
            DB::rollBack();
            return  ['data'=> null, 'message' => Message::error()];
        }
    }

    public function getById($id)
    {
        $user = Auth::user();
        $course = $this->model->with('subject')->find($id);
        if($user->hasRole(UserRoleNameContants::STUDENT))
            $course->isRequestSwitch =  $this->requestModel->where('user_request_id', $user->id)->where('content->old_course_id', $id)->where('type',RequestTypeContants::SWITCH_COURSE)->where('status', RequestStatusContants::PENDING)->count();
        return $course;
    }

    public function getAllActive()
    {
        return $this->model->where('status', StatusTypeContants::ACTIVE)->with('subject')->get();
    }
}

<?php

namespace App\Services;


use App\Enums\StatusTypeContants;
use App\Enums\TimeConstants;
use App\Models\Course;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseService extends BaseService
{
    protected $userCourseService;

    public function __construct(UserCourseService $userCourseService)
    {
        parent::__construct();
        $this->userCourseService = $userCourseService;

    }
    public function getModel()
    {
        return Course::class;
    }

    public function getTable($input, $subjectId)
    {
        $query = $this->model->year($input)->with('homeroomTeacher')->withCount('exam')->withCount('teachers')->withCount('students')->with('subject')->with('schedules', function($query){
            $query->orderByRaw("FIELD(weekday, '".implode("', '", TimeConstants::WEEKDAY)."')");
        });
        if($subjectId != null)
            $query = $query->where('subject_id', $subjectId);
        if(!Auth::user()->isAdministrator())
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
            return ['data'=> $course, 'message'=>"Create successful!"];
        } catch(Exception $e) {
            DB::rollBack();
            return ['data'=> null, 'message'=>"Error, please try again later!"];
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
            return ['data'=>$course, 'message'=>"Update successful!"];
        }catch(Exception $e){
            DB::rollBack();
            return  ['data'=> null, 'message'=>"Error, please try again later!"];
        }
    }

    public function getById($id)
    {
        return $this->model->with('subject')->find($id);
    }
}

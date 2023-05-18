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
use Carbon\Carbon;
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

    public function getTable($request)
    {
        $user = Auth::user();
        $subjectId = $request->subjectId;
        $year = $request->year;
        $query = $this->model->year($year)->subject($subjectId)->with('homeroomTeacher')->withCount('exams')->withCount('teachers')->withCount('students')->with('subject')->with('schedules', function($query){
            $query->orderByRaw("FIELD(weekday, '".implode("', '", TimeConstants::WEEKDAY)."')");
        });
        if(!$user->hasRole(UserRoleNameContants::ADMIN))
         $query = $query->whereHas('userCourses', function($query) use($user) {
            $query->where('user_id', $user->id);
         });
        $result = $this->orderNSearch($request, $query);
        $courses = $result['data'];
        $data = [];
        foreach($courses as $course)
        {
            $schedules = [];
            foreach($course->schedules as $schedule)
            {
                $schedules[] = [
                    'start' => $schedule->start_time,
                    'end' => $schedule->finish_time,
                    'weekday' => $schedule->weekday
                ];
            }
            $data[] = [
                'id' => $course->id,
                'name' => $course->name,
                'subject' => $course->subject->name,
                'schedules' => $schedules,
                'year' => Carbon::parse($course->created_at)->year,
                'status' => $course->status,
                'teachersCount' => $course->teachers_count,
                'studentsCount' => $course->students_count,
                'examsCount' => $course->exams_count,
            ];
        }
        $result['data'] = $data;
        return $result;
    }

    public function getAllCoursesOfUser()
    {
        $user = Auth::user();
        $query = $this->model->with('subject');
        if(!$user->hasRole(UserRoleNameContants::ADMIN))
         $query = $query->whereHas('userCourses', function($query) use($user){
            $query->where('user_id', $user->id);
         });
        $courses = $query->get();
        $data = [];
        foreach($courses as $course)
        {
            $data[] =(object) [
                'id' => $course->id,
                'name' => $course->name.' - '.$course->subject->name
            ];
        }
        return $data;

    }

    public function store($request){
        try{
            DB::beginTransaction();
            $course =  $this->model->create($request->input());
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
        return $this->model->where('id', '!=', $courseId)->where('subject_id', $course->subject_id)->where('status', StatusTypeContants::ACTIVE)->whereDoesntHave('userCourses', function($query) use($userId)
        {
                $query->where('user_id', $userId);
        })->get();
    }

    public function update($id, $request){
        try{
            DB::beginTransaction();
            $arg = [
                'name' => $request->name, 
                'subject_id' => $request->subject_id, 
                'owner_id' => $request->owner_id, 
                'descriptions' => $request->descriptions
            ];
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
            return ['data' => $course, 'message' => Message::updateSuccessfully('course')];
        }catch(Exception $e){
            DB::rollBack();
            return  ['data'=> null, 'message' => Message::error()];
        }
    }

    public function getById($id)
    {
        $user = Auth::user();
        $course = $this->model->with('subject')->withCount('exams')->withCount('teachers')->withCount('students')->with('schedules', function($query){
            $query->orderByRaw("FIELD(weekday, '".implode("', '", TimeConstants::WEEKDAY)."')");
        })->find($id);
        if($user->hasRole(UserRoleNameContants::STUDENT))
            $course->isRequestSwitch =  $this->requestModel->where('user_request_id', $user->id)->where('content->old_course_id', $id)->where('type',RequestTypeContants::SWITCH_COURSE)->where('status', RequestStatusContants::PENDING)->count();
        return $course;
    }

    public function getAllActive()
    {
        return $this->model->where('status', StatusTypeContants::ACTIVE)->with('subject')->get();
    }

    public function getAllActiveOfCourse($subjectId)
    {
        return $this->model->select('name')->where('status', StatusTypeContants::ACTIVE)->where('subject_id',$subjectId)->get();
    }
}

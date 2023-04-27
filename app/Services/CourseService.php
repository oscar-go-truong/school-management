<?php

namespace App\Services;


use App\Enums\StatusTypeContants;
use App\Models\Course;
use Exception;
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

    public function getTable($input)
    {
        $query = $this->model->with('homeroomTeacher')->withCount('exam')->withCount('teachers')->withCount('students')->with('subject');
        $subjectId = isset($input['subjectId'])?$input['subjectId']:null;
        if($subjectId != null)
            $query = $query->where('subject_id', $subjectId);
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
}

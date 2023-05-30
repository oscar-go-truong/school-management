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
use App\Models\Schedule;
use App\Models\UserCourse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseService extends BaseService
{
    protected $scheduleModel;
    protected $userCourseModel;
    protected $requestModel;

    public function __construct(Schedule $scheduleModel, UserCourse $userCourseModel, Request $requestModel)
    {
        parent::__construct();
        $this->scheduleModel = $scheduleModel;
        $this->userCourseModel = $userCourseModel;
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

        $query = $this->model
        ->year($year)
        ->subject($subjectId)
        ->with('homeroomTeacher')
        ->withCount('exams')
        ->withCount('teachers')
        ->withCount('students')
        ->with('subject')
        ->with(['schedules' => function ($query) {
            $query->orderByRaw("FIELD(weekday, '" . implode("', '", TimeConstants::WEEKDAY) . "')");
        }]);

        if (!$user->hasRole(UserRoleNameContants::ADMIN)) {
            $query->whereHas('userCourses', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $result = $this->orderNSearch($request, $query);
        $courses = $result['data'];

        $data = array_map(function ($course) {
            $schedules = $course->schedules->map(function ($schedule) {
                return [
                'start' => $schedule->start_time,
                'end' => $schedule->finish_time,
                'weekday' => $schedule->weekday,
                'room' => $schedule->room->name
                ];
            });

            return [
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
        }, $courses);

        $result['data'] = $data;

        return $result;
    }


    public function getAllCoursesOfUser()
    {
        $user = Auth::user();

        $query = $this->model->with('subject');

        if (!$user->hasRole(UserRoleNameContants::ADMIN)) {
            $query = $query->whereHas('userCourses', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $courses = $query->get();

        return $courses->map(function ($course) {
            return (object) [
                'id' => $course->id,
                'name' => $course->name . ' - ' . $course->subject->name
            ];
        });
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            $course =  $this->model->create($request->input());
            $this->userCourseModel->create([
                    'user_id' => $course->owner_id,
                    'course_id' => $course->id,
                ]);
            $schedules = [];
            foreach (json_decode($request->schedules) as $schedule) {
                $schedules[] = [
                    'course_id' => $course->id,
                    'start_time' => $schedule->start_time,
                    'finish_time' => $schedule->finish_time,
                    'room_id' => $schedule->room,
                    'weekday' => $schedule->weekday,
                    'created_at' => now()->format('Y-m-d H:i:s')
                ];
            }
            $this->scheduleModel->insert($schedules);
            DB::commit();
            return [
                'data' => $course,
                'message' => Message::createSuccessfully('course')
                ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'data' => null,
                'message' => Message::error()
            ];
        }
    }

    public function coursesAvailableSwicth($courseId, $userId)
    {
        $course = $this->model->find($courseId);

        return $this->model
        ->where('id', '!=', $courseId)
        ->where('subject_id', $course
        ->subject_id)->where('status', StatusTypeContants::ACTIVE)
        ->whereDoesntHave('userCourses', function ($query) use ($userId) {
                $query->where('user_id', $userId);
        })
        ->get();
    }

    public function update($id, $request)
    {
        try {
            DB::beginTransaction();
            $arg = [
                'name' => $request->name,
                'subject_id' => $request->subject_id,
                'owner_id' => $request->owner_id,
                'descriptions' => $request->descriptions
            ];

            $this->model->where('id', $id)->update($arg);

            $course = $this->model->find($id);

            $teacherWasJoinedCourse = $this->userCourseModel->where('user_id', $course->owner_id)->where('course_id', $course->id)->count();
            if (!$teacherWasJoinedCourse) {
                $this->userCourseModel->create([
                    'user_id' => $course->owner_id,
                    'course_id' => $course->id,
                ]);
            }

            $this->scheduleModel->where('course_id', $course->id)->delete();

            $schedules = [];
            foreach (json_decode($request->schedules) as $schedule) {
                $schedules[] = [
                    'course_id' => $course->id,
                    'start_time' => $schedule->start_time,
                    'finish_time' => $schedule->finish_time,
                    'room_id' => $schedule->room,
                    'weekday' => $schedule->weekday,
                    'created_at' => now()->format('Y-m-d H:i:s')
                ];
            }
            $this->scheduleModel->insert($schedules);

            DB::commit();
            return ['data' => $course, 'message' => Message::updateSuccessfully('course')];
        } catch (Exception $e) {
            DB::rollBack();
            return  ['data' => null, 'message' => Message::error()];
        }
    }

    public function getById($id)
    {
        $user = Auth::user();

        $course = $this->model
                ->with('subject')
                ->withCount('exams')
                ->withCount('teachers')
                ->withCount('students')
                ->with('schedules', function ($query) {
                                                        $query->orderByRaw("FIELD(weekday, '" . implode("', '", TimeConstants::WEEKDAY) . "')");
                })
                ->find($id);
        if ($user->hasRole(UserRoleNameContants::STUDENT)) {
            $course->isRequestSwitch =  $this->requestModel
                                        ->where('user_request_id', $user->id)
                                        ->where('content->old_course_id', $id)
                                        ->where('type', RequestTypeContants::SWITCH_COURSE)
                                        ->where('status', RequestStatusContants::PENDING)
                                        ->count();
        }

        return $course;
    }

    public function getAllActive()
    {
        return $this->model->where('status', StatusTypeContants::ACTIVE)->with('subject')->get();
    }

    public function getAllActiveOfCourse($subjectId)
    {
        return $this->model->select('name')->where('status', StatusTypeContants::ACTIVE)->where('subject_id', $subjectId)->get();
    }
}

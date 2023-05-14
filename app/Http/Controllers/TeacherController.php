<?php

namespace App\Http\Controllers;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleContants;
use App\Services\CourseService;
use App\Services\UserCourseService;
use App\Services\UserService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    protected $courseService;
    protected $userCourseService;

    protected $userService;

    public function __construct(CourseService $courseService, UserCourseService $userCourseService, UserService $userService)
    {
        $this->courseService = $courseService;
        $this->userCourseService = $userCourseService;
        $this->userService = $userService;
    }
    public function index($courseId){
        $teachers = $this->userService->getUserCanJoinToCourseByRole($courseId,'teacher');
        $course = $this->courseService->getById($courseId);
        return view('teacher.index', compact('courseId','course','teachers'));
    }

    public function getTable(Request $request, $courseId){
        $teachers = $this->userCourseService->getTable($request, $courseId, 'teacher');
        return $teachers;
    }

    public function store(Request $request) {
        $input = $request->input();
        $input['status'] = StatusTypeContants::ACTIVE; 
        $resp = $this->userCourseService->store($input);
        return response()->json($resp);
    }
}

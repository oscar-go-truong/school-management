<?php

namespace App\Http\Controllers;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleContants;
use App\Services\CourseService;
use App\Services\UserCourseService;
use App\Services\UserService;
use Illuminate\Http\Request;

class StudentController extends Controller
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
        $course = $this->courseService->getById($courseId);
        $students = $this->userService->getUserCanJoinToCourseByRole($courseId, UserRoleContants::STUDENT);
        return view('student.index', compact('courseId','course','students'));
    }

    public function getTable(Request $request, $courseId){
        $students = $this->userCourseService->getTable($request, $courseId, UserRoleContants::STUDENT);
        return $students;
    }

    public function store(Request $request){
        $input = $request->input();
        $input['status'] = StatusTypeContants::ACTIVE;
        $resp = $this->userCourseService->store($input);
        return $resp;
    }
}

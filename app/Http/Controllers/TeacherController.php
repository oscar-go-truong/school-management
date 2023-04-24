<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleContants;
use App\Services\CourseService;
use App\Services\UserCourseService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    protected $courseService;
    protected $userCourseService;

    public function __construct(CourseService $courseService, UserCourseService $userCourseService)
    {
        $this->courseService = $courseService;
        $this->userCourseService = $userCourseService;
    }
    public function index($courseId){
        $course = $this->courseService->getById($courseId);
        return view('teacher.index', compact('courseId','course'));
    }

    public function getTable(Request $request, $courseId){
        $teachers = $this->userCourseService->getTable($request, $courseId, UserRoleContants::TEACHER);
        return $teachers;
    }
}

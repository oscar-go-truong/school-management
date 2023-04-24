<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use App\Services\UserCourseService;
use Illuminate\Http\Request;

class StudentController extends Controller
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
        return view('student.index', compact('courseId','course'));
    }

    public function getTable(Request $request, $courseId){
        $students = $this->userCourseService->getStudents($request, $courseId);
        return $students;
    }
}

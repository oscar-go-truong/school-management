<?php

namespace App\Http\Controllers;

use App\Enums\RequestStatusContants;
use App\Enums\RequestTypeContants;
use App\Enums\StatusTypeContants;
use App\Enums\UserRoleNameContants;
use App\Helpers\Message;
use App\Services\CourseService;
use App\Services\RequestService;
use App\Services\UserCourseService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected $courseService;
    protected $userCourseService;

    protected $userService;

    protected $requestService;

    public function __construct(CourseService $courseService, UserCourseService $userCourseService, UserService $userService, RequestService $requestService)
    {
        $this->courseService = $courseService;
        $this->userCourseService = $userCourseService;
        $this->userService = $userService;
        $this->requestService = $requestService;
    }
    public function index($courseId){
        $course = $this->courseService->getById($courseId);
        $students = $this->userService->getUserCanJoinToCourseByRole($courseId, UserRoleNameContants::STUDENT);
        return view('student.index', compact('courseId','course','students'));
    }

    public function getTable(Request $request, $courseId){
        $students = $this->userCourseService->getTable($request, $courseId, UserRoleNameContants::STUDENT);
        return $students;
    }

    public function store(Request $request){
        $resp = $this->userCourseService->store($request);
        return $resp;
    }

    public function changeCourse(Request $request)
    {
        $resp = $this->requestService->storeCreateNApproveSwitchClassRequest($request);
        if($resp['data'] === null)
            return response()->json(['data' => null, 'message' => Message::error()]);
        return response()->json($resp);
    }
}

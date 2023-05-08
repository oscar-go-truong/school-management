<?php

namespace App\Http\Controllers;

use App\Enums\RequestStatusContants;
use App\Enums\RequestTypeContants;
use App\Enums\StatusTypeContants;
use App\Enums\UserRoleContants;
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

    public function changeCourse(Request $request)
    {
        $user = Auth::user();
        $input = $request->input();
        $switchClassRequest = [
                                'user_request_id' =>  $input['user_request_id'], 
                                'user_approve_id' => $user->id, 
                                'type' => RequestTypeContants::SWITCH_COURSE, 
                                'status' => RequestStatusContants::APPROVED, 
                                'old_course_id' => $input['oldCourseId'], 
                                'new_course_id' => $input['newCourseId'] 
                              ];
        $createRequestResp = $this->requestService->storeSwitchClassRequest($switchClassRequest);
        if($createRequestResp['data'] === null)
            return response()->json(['data' => null, 'message' => "Error, please try again later!"]);
        $arg = ['id' => $input['id'], 'course_id' => $input['newCourseId'],'user_id' => $input['user_request_id']];
        $resp = $this->userCourseService->update($input['id'], $arg);
        return response()->json($resp);
    }
}

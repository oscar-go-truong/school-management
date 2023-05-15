<?php

namespace App\Http\Controllers;

use App\Services\UserCourseService;
use Illuminate\Http\Request;

class UserCourseController extends Controller
{
    protected $userCourseService;

    public function __construct(UserCourseService $userCourseService)
    {
        $this->userCourseService = $userCourseService;
    }

    public function changeStatus(Request $request,$id)
    {
        $status = $request->status;
        $resp = $this->userCourseService->changeStatus($id, $status);
        return response()->json($resp);
    }

    public function delete($id)
    {
        $resp = $this->userCourseService->destroy($id);
        return response()->json($resp);
    }
}

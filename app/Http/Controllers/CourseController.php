<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use App\Services\ExamService;
use App\Services\UserCourseService;
use Doctrine\DBAL\Types\JsonType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Util\Json;

class CourseController extends Controller
{
    protected $courseService;
    protected $userCourseService;
    protected $examService;

    public function __construct(CourseService $courseService, UserCourseService $userCourseService,ExamService $examService)
    {
        $this->courseService = $courseService;
        $this->userCourseService = $userCourseService;
        $this->examService = $examService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : View
    {
        return view('course.index');
    }

    public function getTable(Request $request) 
    {
        $courses = $this->courseService->getTable($request);
        return response()->json($courses);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) : View
    {
        $course = $this->courseService->getById($id);
        return view('course.detail', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) : JsonResponse
    {
        $resp = $this->courseService->destroy($id);
        return response()->json($resp);
    }

    // change course status
    public function changeStatus(Request $request, int $id)
    {
        $status = $request->status;
        $resp = $this->courseService->changeStatus($id, $status);
        return response()->json($resp);
    }
}

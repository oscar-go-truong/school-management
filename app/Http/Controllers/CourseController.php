<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use App\Services\ExamService;
use App\Services\UserCourseService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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

    public function getExams($id) {
       return view('exam.index', compact('API'));  
    }

    public function getExamsTable(Request $request ,$id){
        $exams = $this->examService->getCourseExams($request, $id);
        return $exams;
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
    public function destroy($id)
    {
        $course = $this->courseService->destroy(($id));
        if($course !== null)
            return response()->json(['data'=>$course, 'message'=>"Delete course successful!"]);
        return response()->json(['data'=>$course, 'message'=>"Error, please try again later!"]);
    }

    // change course status
    public function changeStatus(Request $request, int $id)
    {
        $status = $request->status;
        $course = $this->courseService->changeStatus($id, $status);
        if($course !== null)
            return response()->json(['data'=>$course, 'message'=>"Update course successful!"]);
        return response()->json(['data'=>$course, 'message'=>"Error, please try again later!"]);
       
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleContants;
use App\Http\Requests\CreateUpdateCourseRequest;
use App\Services\CourseService;
use App\Services\ExamService;
use App\Services\SubjectService;
use App\Services\UserCourseService;
use App\Services\UserService;
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
    protected $userService;
    protected $subjectService;

    public function __construct(CourseService $courseService, 
                                UserCourseService $userCourseService,
                                ExamService $examService, 
                                UserService $userService, 
                                SubjectService $subjectService)
    {
        $this->courseService = $courseService;
        $this->userCourseService = $userCourseService;
        $this->examService = $examService;
        $this->userService = $userService;
        $this->subjectService = $subjectService;
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
        $input = $request->input();
        $courses = $this->courseService->getTable($input);
        return response()->json($courses);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() : View
    {
        $teachers = $this->userService->getByRole(UserRoleContants::TEACHER);
        $subjects = $this->subjectService->getAll();
        return view('course.create', compact('teachers','subjects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUpdateCourseRequest $request)
    {
        $resp = $this->courseService->store($request->input());
        if($resp['data'] != null)
            return redirect('/courses')->with('success',$resp['message']);
        else 
            return redirect()->back()->with('error',$resp['message']);
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
    public function edit($id) : View
    {
        $teachers = $this->userService->getByRole(UserRoleContants::TEACHER);
        $subjects = $this->subjectService->getAll();
        $course = $this->courseService->getById($id);
        return view('course.update', compact('course','teachers','subjects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateUpdateCourseRequest $request, $id)
    {
        $input = $request->input();
        $arg = array('name' => $input['name'], 'subject_id' => $input['subject_id'], 'owner_id'=>$input['owner_id'], 'descriptions' => $input['descriptions']);
        $resp = $this->courseService->update($id, $arg);
        if($resp['data'] != null)
            return redirect('/courses')->with('success',$resp['message']);
        else 
            return redirect()->back()->with('error',$resp['message']);
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

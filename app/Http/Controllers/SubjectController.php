<?php

namespace App\Http\Controllers;

use App\Enums\APIUrlEnums;
use App\Enums\StatusType;
use App\Services\CourseService;
use App\Services\SubjectService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected $subjectService;
    protected $courseService;

    public function __construct(SubjectService $subjectService, CourseService $courseService)
    {
        $this->subjectService = $subjectService;
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $API = APIUrlEnums::TABLE_SUBJECT_API;
        return view('subject.index', compact('API'));
    }

    public function getTable(Request $request)
    {
        $subjects = $this->subjectService->getTable($request);
        return response()->json($subjects);
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
    public function show($id): View
    {
        $subject = $this->subjectService->getById($id);
        return view('subject.detail', compact('subject'));
    }

    public function getCourses($id): View
    {
        $API = '/subjects/' . $id . '/courses/table';
        return view('course.index', compact('API'));
    }
    public function getCoursesTable(Request $request, $id)
    {
        $courses = $this->courseService->getCoursesBySubjectId($request, $id);
        return response()->json($courses);
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

    public function changeStatus(Request $request, $id)
    {
        $status = $request->input('status', StatusType::ACTIVE);
        $subject = $this->subjectService->changeStatus($id, $status);
        if ($subject != null) {
            return response()->json(['data' => $subject, 'message' => "Update subject status successful!"]);
        } else {
            return response()->json(['data' => $subject, 'message' => "Error, please try again later!"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subject = $this->subjectService->destroy($id);
        if ($subject != null) {
            return response()->json(['data' => $subject, 'message' => "Delete subject successful!"]);
        } else {
            return response()->json(['data' => $subject, 'message' => "Error, please try again later!"]);
        }
    }
}

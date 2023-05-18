<?php

namespace App\Http\Controllers;

use App\Enums\MyExamTypeConstants;
use App\Enums\StatusTypeContants;
use App\Services\CourseService;
use App\Services\ExamService;
use App\Services\ScoreService;
use App\Services\SubjectService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $examService;
    protected $courseService;

    protected $subjectService;
    protected $scoreService;

    public function __construct(ExamService $examService, CourseService $courseService,SubjectService $subjectService , ScoreService $scoreService)
    {
        $this->examService = $examService;
        $this->courseService = $courseService;
        $this->subjectService = $subjectService;
        $this->scoreService  = $scoreService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) :View
    {
        $currentYear = date('Y');
        $years = range($currentYear, 2020);
        $courseId = $request->query('courseId', null);
        $courses = $this->courseService->getAllCoursesOfUser();
        return view('exam.index', compact('courseId', 'courses','years'));
    }

    public function getTable(Request $request)
    {
        $exams = $this->examService->getTable($request);
        return response()->json($exams);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : JsonResponse
    {
       $resp = $this->examService->store($request);
       return response()->json($resp);
    }


    public function importScores(Request $request,$id)
    {
        $result = $this->scoreService->importScores($id, $request);
        return response()->json($result);
    }

    public function detachFile(Request $request, $id)
    {
        $result = $this->scoreService->detachFile($id, $request);
        return response()->json($result);
    }

    public function getMissingUser(Request $request)
    {
        $result = $this->scoreService->getMissingUser($request);
        return response()->json($result);
    }
}

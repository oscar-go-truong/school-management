<?php

namespace App\Http\Controllers;

use App\Enums\MyExamTypeConstants;
use App\Enums\StatusTypeContants;
use App\Services\CourseService;
use App\Services\ExamService;
use App\Services\ScoreService;
use Doctrine\DBAL\Types\JsonType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $examService;
    protected $courseService;
    protected $scoreService;

    public function __construct(ExamService $examService, CourseService $courseService,ScoreService $scoreService)
    {
        $this->examService = $examService;
        $this->courseService = $courseService;
        $this->scoreService  = $scoreService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) :View
    {
        $courseId = $request->query('courseId', null);
        $course = $courseId===null? null :$this->courseService->getById($courseId);
        $examTypes = MyExamTypeConstants::asArray();
        return view('exam.index', compact('examTypes','course'));
    }

    public function getTable(Request $request)
    {
        $input = $request->input();
        $exams = $this->examService->getTable($input);
        return response()->json($exams);
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
       $input = $request->input();
       $input['status'] = StatusTypeContants::ACTIVE;
       $resp = $this->examService->store($input);
       return response()->json($resp);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    public function importScores(Request $request,$id)
    {
        $input = $request->input();
        $result = $this->scoreService->importScores($id, $input);
        return response()->json($result);
    }

    public function detachFile(Request $request, $id)
    {
        $input = $request->input();
        $result = $this->scoreService->detachFile($id, $input);
        return response()->json($result);
    }

    public function getMissingUser(Request $request)
    {
        $input = $request->input();
        $result = $this->scoreService->getMissingUser($input);
        return response()->json($result);
    }
}

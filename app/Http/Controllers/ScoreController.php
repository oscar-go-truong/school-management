<?php

namespace App\Http\Controllers;

use App\Enums\MyExamTypeConstants;
use App\Models\Exam;
use App\Services\ExamService;
use App\Services\ScoreService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    protected $scoreService;
    protected $examService;

    public function __construct(ScoreService $scoreService,ExamService $examService)
    {
        $this->scoreService = $scoreService;
        $this->examService = $examService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $examId) : View
    {
        $exam = $this->examService->getById($examId);
        $exam->type = ucfirst(strtolower(MyExamTypeConstants::getKey($exam->type)));
        return view('score.index', compact('exam'));
    }

    public function getTable(Request $request, $examId)
    {
        $input = $request->input();
        $scores = $this->scoreService->getTable($input, $examId);
        return response()->json($scores);
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
}

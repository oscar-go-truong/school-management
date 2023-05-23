<?php

namespace App\Http\Controllers;

use App\Services\ExamService;
use App\Services\ScoreService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    protected $scoreService;
    protected $examService;

    public function __construct(ScoreService $scoreService, ExamService $examService)
    {
        $this->scoreService = $scoreService;
        $this->examService = $examService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $examId): View
    {
        $exam = $this->examService->getById($examId);
        return view('score.index', compact('exam'));
    }

    public function getTable(Request $request, $examId)
    {
        $scores = $this->scoreService->getTable($request, $examId);
        return response()->json($scores);
    }
}

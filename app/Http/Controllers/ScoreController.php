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

    public function edit($key)
    {
        $score = $this->scoreService->getByEditKey($key);
        if ($score) {
            return view('score.update', compact('score'));
        }
        return abort(404);
    }

    public function update(Request $request)
    {
        $resp = $this->scoreService->updateReview($request);
        if ($resp['data'] != null) {
            return redirect('/exams/' . $resp['data'] . '/scores')->with('success', $resp['message']);
        } else {
            return redirect()->back()->with('error', $resp['message']);
        }
    }
}

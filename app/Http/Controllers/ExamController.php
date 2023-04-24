<?php

namespace App\Http\Controllers;

use App\Services\ExamService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function index($courseId) {
       return view('exam.index', compact('courseId'));  
    }

    public function getTable(Request $request ,$id){
        $exams = $this->examService->getCourseExams($request, $id);
        return response()->json($exams);
    }
}

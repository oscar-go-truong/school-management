<?php

namespace App\Http\Controllers;

use App\Services\OutcomeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutcomeController extends Controller
{
    protected $outComeService;

    public function __construct(OutcomeService $outComeService)
    {
        $this->outComeService = $outComeService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $currentYear = date('Y');
        $years = range($currentYear, 2020);
        return view('outcome.index', compact('years'));
    }

    public function getTable(Request $request)
    {
        $outcomes = $this->outComeService->getTable($request);
        return response()->json($outcomes);
    }

    public function export(Request $request)
    {
        $outcomes = $this->outComeService->getTable($request);
        $user = Auth::user();
        $year = $request->year;
        $data = [
            'outcomes' => $outcomes,
            'year' => $year,
            'student' => $user
        ];
        $pdf = Pdf::loadView('exports.studentOutcomesLetter', $data);
        $fileName = $user->fullname . ' outcomes' . ($year ? ' ' . $year : '') . '.pdf';
        return $pdf->download($fileName);
    }
}

<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentsListExport implements FromView,ShouldAutoSize
{
    protected $students;
    public function __construct($students)
    {
        $this->students = $students;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $students = $this->students;
        return view('exports.studentsListFileExport', compact('students'));
    }
}

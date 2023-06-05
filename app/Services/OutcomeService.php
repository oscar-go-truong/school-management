<?php

namespace App\Services;

use App\Enums\GradeContants;
use App\Enums\UserRoleNameContants;
use App\Helpers\Message;
use App\Models\Course;
use App\Models\Score;
use Illuminate\Support\Facades\Auth;

class OutcomeService extends BaseService
{
    protected $courseModel;

    public function __construct(Course $courseModel)
    {
        parent::__construct();
        $this->courseModel = $courseModel;
    }

    public function getModel()
    {
        return Score::class;
    }
    protected function grading($gradePoints)
    {
        foreach (GradeContants::asArray() as $key => $grade) {
            if ($gradePoints >= $grade['gradePoints']) {
                return (object)[
                    'gradeLetter' => $grade['gradeLetter'],
                    'grade' => ucwords(strtolower(str_replace('_', ' ', $key)))
                ];
            }
        }
    }


    public function getTable($request)
    {
        $user = Auth::user();

        if (!$user->hasRole(UserRoleNameContants::STUDENT)) {
            return ['data' => null, Message::error()];
        }

        $year = $request->year;
        $courses = $this->courseModel->year($year)->whereHas('userCourses', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('exams', function ($query) use ($user) {
            $query->with('scores', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            });
        })->with('homeroomTeacher')->orderBy('created_at', 'desc')->get();


        $data = [];

        foreach ($courses as $course) {
            $exams = $course->exams;
            if (count($exams)) {
                $sum = 0;
                $coef = 0;
                foreach ($exams as $exam) {
                    if (count($exam->scores)) {
                        $sum += $exam->scores[0]->total * $exam->type;
                        $coef += $exam->type;
                    } else {
                        $sum = null;
                        break;
                    }
                }
                $gradePoints = null;
                if ($sum !== null) {
                    $gradePoints = round($sum / $coef, 1);
                }
            } else {
                $gradePoints = null;
            }

            $data[] = (object)[
                'id' => $course->id,
                'subject' => $course->subject->name,
                'course' => $course->name,
                'homeroom_teacher' => $course->homeroomTeacher->fullname,
                'grade_points' => $gradePoints,
                'grade_letter' => $gradePoints !== null ? $this->grading($gradePoints)->gradeLetter : null,
                'grade' => $gradePoints !== null ? $this->grading($gradePoints)->grade : null
            ];
        }

        return $data;
    }
}

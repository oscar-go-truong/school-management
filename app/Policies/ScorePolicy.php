<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Exam;
use App\Models\Score;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScorePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Score $score): bool
    {
        if ($user->role !== UserRole::TEACHER) {
            return false;
        }
        $course_id = Exam::where("id", $score->exam_id)->exec()->coure_id;
        if (!UserCourse::where('course_id', $course_id)->where('user_id', $user->id)->exists()) {
            return false;
        }
        return true;
    }
}

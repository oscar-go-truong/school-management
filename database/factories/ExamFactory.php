<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\MyExamTypeConstants;
use App\Enums\StatusTypeContants;
use App\Models\Course;

class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $course = Course::all()->random();
        return [
            'type' => MyExamTypeConstants::getRandomValue(),
            'status' => StatusTypeContants::ACTIVE,
          'course_id' => $course->id,
          'can_edit_scores' => false
        ];
    }
}

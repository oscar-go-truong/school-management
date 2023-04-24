<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ExamTypeConstants;
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
        return [
            'type' => ExamTypeConstants::getRandomValue(),
            'status' => StatusTypeContants::ACTIVE,
          'course_id' => Course::all()->random()->id,
        ];
    }
}

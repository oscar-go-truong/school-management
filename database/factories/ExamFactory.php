<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ExamType;
use App\Enums\StatusType;
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
            'type' => ExamType::getRandomValue(),
            'status' => StatusType::ACTIVE,
          'course_id' => Course::all()->random()->id,
        ];
    }
}

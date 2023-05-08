<?php

namespace Database\Factories;

use App\Enums\TimeConstants;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start_time' => collect(TimeConstants::TIMES)->random(),
            'finish_time' => collect(TimeConstants::TIMES)->random(),
            'course_id' => Course::all()->random(),
            'weekday' => collect(TimeConstants::WEEKDAY)->random(),
        ];
    }
}

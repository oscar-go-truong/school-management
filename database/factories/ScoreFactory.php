<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ScoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'total' => collect([1,2,3,4,5,6,7,8,9,10])->random(),
          'student_id' => \App\Models\User::all()->random()->id,
          'exam_id' => \App\Models\Exam::all()->random()->id,
        ];
    }
}

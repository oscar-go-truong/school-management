<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'type' => collect([1,2,3])->random(),
            'status' => 1, 
          'class_id' => \App\Models\Classes::all()->random()->id,
        ];
    }
}

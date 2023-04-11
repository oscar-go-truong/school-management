<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassesFactory extends Factory
{
   
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->userName(),
            'status' => 1,
            'descriptions' => $this->faker->sentence(),
          'owner_id' => \App\Models\User::where('role', 2)
          ->get()->random()->id,
          'subject_id' => \App\Models\Subject::all()->random()->id,
        ];
    }
}

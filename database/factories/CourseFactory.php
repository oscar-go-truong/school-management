<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subject;
use App\Models\User;

class CourseFactory extends Factory
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
          'owner_id' => User::where('role', 2)
          ->get()->random()->id,
          'subject_id' => Subject::all()->random()->id,
        ];
    }
}

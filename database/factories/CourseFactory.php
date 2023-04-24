<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\StatusType;
use App\Enums\UserRole;
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
           'status' => StatusType::ACTIVE,
            'descriptions' => $this->faker->text(),
          'owner_id' => User::where('role', UserRole::TEACHER)
          ->get()->random()->id,
          'subject_id' => Subject::all()->random()->id,
        ];
    }
}

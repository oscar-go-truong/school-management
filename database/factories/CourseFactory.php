<?php

namespace Database\Factories;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleNameContants;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
           'status' => StatusTypeContants::ACTIVE,
            'descriptions' => $this->faker->sentence(),
          'owner_id' => User::role(UserRoleNameContants::TEACHER)
          ->get()->random()->id,
          'subject_id' => Subject::all()->random()->id,
        ];
    }
}

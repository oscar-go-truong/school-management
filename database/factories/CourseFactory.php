<?php

namespace Database\Factories;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleContants;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

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
          'owner_id' => User::where('role', UserRoleContants::TEACHER)
          ->get()->random()->id,
          'subject_id' => Subject::all()->random()->id,
        ];
    }
}

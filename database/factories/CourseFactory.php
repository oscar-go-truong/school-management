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
          'start_time' => Carbon::createFromFormat('H:i:s',collect(['9:00:00', '10:30:00'])->random()),
          'finish_time' => Carbon::createFromFormat('H:i:s',collect(['13:30:00', '15:00:00'])->random()),
          'weekday' => collect(['Mon', 'Tue','Wed','Thu','Fri'])->random()
        ];
    }
}

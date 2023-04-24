<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\StatusTypeContants;
use App\Enums\UserRoleContants;
use App\Models\User;
use App\Models\Course;
class UserCourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::where('role',UserRoleContants::STUDENT)->inRandomOrder()->first()->id,
            'course_id' => Course::all()->random()->id,
            'status' => StatusTypeContants::getRandomValue(),
        ];
    }
}

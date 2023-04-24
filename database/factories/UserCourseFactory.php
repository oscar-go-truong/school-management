<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\StatusType;
use App\Enums\UserRole;
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
            'user_id' => User::all()->random()->id,
            'course_id' => Course::all()->random()->id,
            'status' => StatusType::getRandomValue(),
            'role' => collect([UserRole::TEACHER,UserRole::STUDENT])->random()
        ];
    }
}

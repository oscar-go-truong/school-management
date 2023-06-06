<?php

namespace Database\Factories;

use App\Enums\UserRoleNameContants;
use App\Models\UserCourse;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\StatusTypeContants;
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
        do{
            $userId = User::role(UserRoleNameContants::STUDENT)->inRandomOrder()->first()->id;
            $courseId = Course::all()->random()->id;
        } while(UserCourse::where('user_id', $userId)->where('course_id', $courseId)->count());
        return [
            'user_id' => $userId,
            'course_id' => $courseId,
            'status' => StatusTypeContants::getRandomValue(),
        ];
    }
}

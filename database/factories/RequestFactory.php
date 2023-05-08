<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\RequestTypeContants;
use App\Models\User;

class RequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => RequestTypeContants::getRandomValue(),
            'status' => RequestTypeContants::getRandomValue(),
            'user_request_id' => collect([2,3])->random(),
            'user_approve_id' => 1,
        ];
    }
}

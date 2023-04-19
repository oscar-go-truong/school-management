<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\RequestType;
use App\Enums\StatusType;
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
            'type' => RequestType::getRandomValue(),
            'status' => StatusType::getRandomValue(),
            'user_request_id' => User::all()->random()->id,
            'user_approve_id' => 1,
        ];
    }
}

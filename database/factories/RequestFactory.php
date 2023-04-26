<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\RequestTypeContants;
use App\Enums\StatusTypeContants;
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
            'status' => StatusTypeContants::getRandomValue(),
            'user_request_id' => User::all()->random()->id,
            'user_approve_id' => 1,
        ];
    }
}

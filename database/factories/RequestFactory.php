<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'type' => collect([1,2,3])->random(),
            'status' => 1,
            'user_request_id' => \App\Models\User::all()->random()->id,
            'user_approve_id' => 1,
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::all()->random()->id,
            'class_id' => \App\Models\Classes::all()->random()->id,
            'status' => 1,

        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\StatusTypeContants;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
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
            'descriptions' => $this->faker->sentence(),
            'status' => StatusTypeContants::ACTIVE,
        ];
    }
}

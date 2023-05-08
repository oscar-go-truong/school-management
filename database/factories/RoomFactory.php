<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\StatusTypeContants;

class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => collect(['A','B','C','D','E','F','G'])->random().(collect([100,200,300,400,500])->random() + collect([0,1,2,3,4,5,6,7,8,9])->random()),
            'status' => StatusTypeContants::getRandomValue(),
        ];
    }
}

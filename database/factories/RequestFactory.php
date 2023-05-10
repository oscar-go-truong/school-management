<?php

namespace Database\Factories;

use App\Enums\RequestStatusContants;
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
        $status = RequestStatusContants::getRandomValue();
        $type = RequestTypeContants::getRandomValue();
        return [
            'type' => $type,
            'status' => $status,
            'user_request_id' => $type === RequestTypeContants::BOOK_ROOM_OR_LAB ? 2 : 3,
            'user_approve_id' => $status === RequestStatusContants::PENDING ? null : 1,
        ];
    }
}

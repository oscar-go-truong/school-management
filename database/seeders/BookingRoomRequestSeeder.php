<?php

namespace Database\Seeders;

use App\Enums\RequestTypeContants;
use App\Models\BookingRoomRequest;
use App\Models\Course;
use App\Models\Request;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BookingRoomRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();
        $requests = Request::where('type', RequestTypeContants::BOOK_ROOM_OR_LAB)->get();
        foreach($requests as $request)
        {
           $content =   '{"room_id":'.Room::all()->random()->id.
                        ',"course_id":'.Course::all()->random()->id.
                        ',"booking_date_start":"'.$faker->dateTimeBetween('2021-01-01', '2021-12-31')->format('Y-m-d').
                        '","booking_date_finish":"'.$faker->dateTimeBetween('2022-01-01', '2023-12-31')->format('Y-m-d').'"}';
            Request::where('id',$request->id)->update( ['content' => $content]);
        }
    }
}

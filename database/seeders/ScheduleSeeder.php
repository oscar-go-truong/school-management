<?php

namespace Database\Seeders;

use App\Enums\TimeConstants;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = Course::all();
        foreach($courses as $course)
        {
            $random = collect([1,2,3,4,5,6])->random();
            Schedule::insert([
                [
                    'start_time' => (9 + $random).':00:00',
                    'finish_time' => (9 + $random + 1).':30:00',
                    'weekday' => collect(TimeConstants::WEEKDAY)->random(),
                    'course_id' => $course->id
                ],
                [
                    'start_time' => (9 + $random + 2).':00:00',
                    'finish_time' => (9 + $random + 3).':30:00',
                    'weekday' => collect(TimeConstants::WEEKDAY)->random(),
                    'course_id' => $course->id
                ]
                ]);
        }
    }
}

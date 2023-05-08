<?php

namespace Database\Seeders;

use App\Enums\RequestTypeContants;
use App\Models\Course;
use App\Models\Request;
use App\Models\SwitchCourseRequest;
use Illuminate\Database\Seeder;

class SwitchCourseRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requests = Request::where('type', RequestTypeContants::SWITCH_COURSE)->get();
        foreach($requests as $request)
        {
            $content = SwitchCourseRequest::create([
                'old_course_id' => Course::all()->random()->id,
                'new_course_id' => Course::all()->random()->id,
            ]);
            Request::where('id',$request->id)->update( ['content_id' => $content->id]);
        }
    }
}

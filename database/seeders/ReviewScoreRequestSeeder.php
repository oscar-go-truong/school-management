<?php

namespace Database\Seeders;

use App\Enums\RequestTypeContants;
use App\Models\Exam;
use App\Models\Request;
use App\Models\ReviewScoreRequest;
use Illuminate\Database\Seeder;

class ReviewScoreRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requests = Request::where('type', RequestTypeContants::REVIEW_GRADES)->get();
        foreach($requests as $request)
        {
           $content =  ReviewScoreRequest::create([
               'exam_id' => Exam::all()->random()->id
            ]);
            Request::where('id',$request->id)->update( ['content_id' => $content->id]);
        }
    }
}

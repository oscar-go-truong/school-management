<?php

namespace Database\Seeders;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleNameContants;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserCourse;
use Carbon\Carbon;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = Subject::all();
        foreach($subjects as $subject) {
            Course::insert(
                [
                        [
                        'name'=> 'Begginer 2020',
                        'subject_id' => $subject->id,
                        'owner_id' => User::role(UserRoleNameContants::TEACHER)->inRandomOrder()->first()->id,
                        'descriptions' => 'Beginner subjects in IT college are designed for students who have little to no experience with computers or technology.',
                        'status'=>StatusTypeContants::INACTIVE,
                        'created_at'=> Carbon::createFromDate(2020),
                        ],
                        [
                            'name'=> 'Begginer 2021',
                            'subject_id' => $subject->id,
                            'owner_id' => User::role(UserRoleNameContants::TEACHER)->inRandomOrder()->first()->id,
                            'descriptions' => 'Beginner subjects in IT college are designed for students who have little to no experience with computers or technology.',
                            'status'=>StatusTypeContants::INACTIVE,
                            'created_at'=> Carbon::createFromDate(2021),
                        ],
                        [
                            'name'=> 'Begginer 2022',
                            'subject_id' => $subject->id,
                            'owner_id' => User::role(UserRoleNameContants::TEACHER)->inRandomOrder()->first()->id,
                            'descriptions' => 'Beginner subjects in IT college are designed for students who have little to no experience with computers or technology.',
                            'status'=>StatusTypeContants::INACTIVE,
                            'created_at'=> Carbon::createFromDate(2022),
                        ],
                        [
                            'name'=> 'Begginer 2023',
                            'subject_id' => $subject->id,
                            'owner_id' => User::role(UserRoleNameContants::TEACHER)->inRandomOrder()->first()->id,
                            'descriptions' => 'Beginner subjects in IT college are designed for students who have little to no experience with computers or technology.',
                            'status'=>StatusTypeContants::ACTIVE,
                            'created_at'=> Carbon::createFromDate(2023),
                        ],
                        [
                            'name'=> 'Basic 2020',
                            'subject_id' => $subject->id,
                            'owner_id' => User::role(UserRoleNameContants::TEACHER)->inRandomOrder()->first()->id,
                            'descriptions' => 'Basic subjects are designed for students who have some experience with computers or technology but may not have a lot of knowledge or skills.',
                            'status'=>StatusTypeContants::INACTIVE,
                            'created_at'=> Carbon::createFromDate(2020),
                        ],
                        [
                            'name'=> 'Basic 2021',
                            'subject_id' => $subject->id,
                            'owner_id' => User::role(UserRoleNameContants::TEACHER)->inRandomOrder()->first()->id,
                            'descriptions' => 'Basic subjects are designed for students who have some experience with computers or technology but may not have a lot of knowledge or skills.',
                            'status'=>StatusTypeContants::INACTIVE,
                            'created_at'=> Carbon::createFromDate(2021),
                        ],
                        [
                            'name'=> 'Basic 2022',
                            'subject_id' => $subject->id,
                            'owner_id' => User::role(UserRoleNameContants::TEACHER)->inRandomOrder()->first()->id,
                            'descriptions' => 'Basic subjects are designed for students who have some experience with computers or technology but may not have a lot of knowledge or skills.',
                            'status'=>StatusTypeContants::INACTIVE,
                            'created_at'=> Carbon::createFromDate(2022),
                        ],
                        [
                            'name'=> 'Basic 2023',
                            'subject_id' => $subject->id,
                            'owner_id' => User::role(UserRoleNameContants::TEACHER)->inRandomOrder()->first()->id,
                            'descriptions' => 'Basic subjects are designed for students who have some experience with computers or technology but may not have a lot of knowledge or skills.',
                            'status'=>StatusTypeContants::ACTIVE,
                            'created_at'=> Carbon::createFromDate(2023),
                        ],
                        [
                            'name'=> 'Advanced 2022',
                            'subject_id' => $subject->id,
                            'owner_id' => User::role(UserRoleNameContants::TEACHER)->inRandomOrder()->first()->id,
                            'descriptions' => 'Advanced subjects are designed for students who have a lot of knowledge and skills in computers or technology and are ready for more challenging coursework.',
                            'status'=>StatusTypeContants::INACTIVE,
                            'created_at'=> Carbon::createFromDate(2022),
                        ],
                        [
                            'name'=> 'Advanced 2023',
                            'subject_id' => $subject->id,
                            'owner_id' => 2,
                            'descriptions' => 'Advanced subjects are designed for students who have a lot of knowledge and skills in computers or technology and are ready for more challenging coursework.',
                            'status'=>StatusTypeContants::ACTIVE,
                            'created_at'=> Carbon::createFromDate(2023),
                        ]
                ]
        );
        }
        $courses = Course::all();
        foreach($courses as $course){
            UserCourse::insert(array('user_id'=> $course->owner_id, 'course_id'=>$course->id, 'status'=>1, 'created_at'=>$course->created_at));
        }
    }
}

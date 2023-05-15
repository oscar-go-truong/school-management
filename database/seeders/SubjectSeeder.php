<?php

namespace Database\Seeders;

use App\Enums\StatusTypeContants;
use Illuminate\Database\Seeder;
use App\Models\Subject;


class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Subject::insert([
        [
        'name'=>'English', 
        'descriptions'=>'Learners will also develop their speaking and listening skills to communicate on familiar and general topics and will be able to read and write short texts on everyday subjects.', 
        'status'=>StatusTypeContants::ACTIVE
        ],
        [
            'name'=>'Database Management Systems (DBMS)', 
            'descriptions'=>'This subject covers the basics of database design and management.', 
            'status'=>StatusTypeContants::ACTIVE
        ],
        [
            'name'=>'Foundations of Information Technology', 
            'descriptions'=>'This subject covers the basics of information technology, including hardware, software, and networking.', 
            'status'=>StatusTypeContants::ACTIVE  
        ],
        [
            'name'=>'Problem-solving Methodologies & Programming in C', 
            'descriptions'=>'This subject covers the basics of programming in C and problem-solving methodologies.', 
            'status'=>StatusTypeContants::ACTIVE  
        ],
        [
            'name'=>'Computer Organization & Architecture', 
            'descriptions'=>'This subject covers the basics of computer organization and architecture.', 
            'status'=>StatusTypeContants::ACTIVE  
        ]   
    ]);
    }
}

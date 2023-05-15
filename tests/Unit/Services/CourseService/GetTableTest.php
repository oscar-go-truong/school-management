<?php

namespace Tests\Unit\Services\CourseService;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleContants;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use App\Services\CourseService;
use App\Services\UserCourseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GetTableTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
      /**
    * @dataProvider getTableProvider
     */

    public function testGetTableSuccess($input, $queryExpected)
    {
      $user = User::insert([
        'username' => 'david kushner',
        'fullname' => 'david kushner',
        'email' => 'user1@gmail.com',
        'password' => Hash::make('password'),
        'status' => StatusTypeContants::ACTIVE,
        'role' => UserRoleContants::TEACHER
      ]);
      $subject = Subject::factory()->create();
      $course = Course::factory()->count(20)->create();
      $expected = $queryExpected();
      $userCourseService = new UserCourseService();
      $courseService = new CourseService($userCourseService);
      $result = $courseService->getTable($input);
      $this->assertEquals($result,$expected);
      
   }

   public function getTableProvider(){
    return [
        'empty'=>[
            [
                
            ],
            function () {
                return Course::with('homeroomTeacher')->withCount('exam')->withCount('teachers')->withCount('students')->with('subject')->paginate(10);
            }
        ],
        'limit'=>[
            [
                'limit' => 10
            ],
            function () {
                return Course::with('homeroomTeacher')->withCount('exam')->withCount('teachers')->withCount('students')->with('subject')->paginate(10);
            }
        ],
        'limit, order'=> [
            [
                'limit' => 10,
                'orderBy' => 'name',
                'orderDirect' => 'desc'
            ],
            function () {
                return Course::orderBy('name','desc')->with('homeroomTeacher')->withCount('exam')->withCount('teachers')->withCount('students')->with('subject')->paginate(10);
            }
        ],
        'limit, order & filter by subject id'=> [
            [
                'limit' => 10,
                'orderBy' => 'name',
                'orderDirect' => 'desc', 
                'subjectId' => 1
            ],
            function () {
                return Course::where('subject_id',1)->orderBy('name','desc')->with('homeroomTeacher')->withCount('exam')->withCount('teachers')->withCount('students')->with('subject')->paginate(10);
            }
        ],
    ];
}
}

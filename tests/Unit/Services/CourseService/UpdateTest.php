<?php

namespace Tests\Unit\Services\CourseService;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleContants;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use App\Services\CourseService;
use App\Services\UserCourseService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testUpdateSuccess()
    {
        $user = User::insertGetId([
            'username' => 'david kushner',
            'fullname' => 'david kushner',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('password'),
            'status' => StatusTypeContants::ACTIVE,
            'role' => UserRoleContants::TEACHER
          ]);
          $subject = Subject::factory()->create();
          $course = [
            'name' => 'example courses',
            'descriptions' => 'this is descriptions for course',
            'owner_id' => $user,
            'subject_id' => $subject->id,
            'status' => StatusTypeContants::ACTIVE
          ];
        $courseId = Course::insertGetId($course);
        $newCourse = [
        'name' => 'example courses new',
        'descriptions' => 'this is new descriptions for course',
        'owner_id' => $user,
        'subject_id' => $subject->id,
        'status' => StatusTypeContants::INACTIVE
        ];
    $mockUserCourseService = Mockery::mock(UserCourseService::class);
    $mockUserCourseService->shouldReceive('store')->andReturn(['data'=>'ok']);
    $mockUserCourseService->shouldReceive('checkUserWasJoinedCourse')->andReturn(1);
    $courseSevice = new CourseService($mockUserCourseService);
    $response = $courseSevice->update($courseId, $newCourse);
    $this->assertEquals($response['message'], "Update successful!");
    $this->assertDatabaseHas('courses',[
        'id'=>$courseId,  
        'name' => 'example courses new',
        'descriptions' => 'this is new descriptions for course',
        'owner_id' => $user,
        'subject_id' => $subject->id,
        'status' => StatusTypeContants::INACTIVE]);
    }

    public function testUpdateRollbackWhenFailed()
    {
        $user = User::insertGetId([
            'username' => 'david kushner',
            'fullname' => 'david kushner',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('password'),
            'status' => StatusTypeContants::ACTIVE,
            'role' => UserRoleContants::TEACHER
          ]);
          $subject = Subject::factory()->create();
          $course = [
            'name' => 'example courses',
            'descriptions' => 'this is descriptions for course',
            'owner_id' => $user,
            'subject_id' => $subject->id,
            'status' => StatusTypeContants::ACTIVE
          ];
        $courseId = Course::insertGetId($course);
        $newCourse = [
        'name' => 'example courses new',
        'descriptions' => 'this is new descriptions for course',
        'owner_id' => $user,
        'subject_id' => $subject->id,
        'status' => StatusTypeContants::INACTIVE
        ];
    $mockUserCourseService = Mockery::mock(UserCourseService::class);
    $mockUserCourseService->shouldReceive('store')->andThrow(new Exception('error'));
    $mockUserCourseService->shouldReceive('checkUserWasJoinedCourse')->andReturn(0);
    $courseSevice = new CourseService($mockUserCourseService);
    $response = $courseSevice->update($courseId, $newCourse);
    $this->assertEquals($response['message'], "Error, please try again later!");
}
}
<?php

namespace Tests\Unit\Services\CourseService;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleContants;
use App\Models\Subject;
use App\Models\User;
use App\Services\CourseService;
use App\Services\UserCourseService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testStoreSuccess()
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
      $mockUserCourseService = Mockery::mock(UserCourseService::class);
      $mockUserCourseService->shouldReceive('store')->andReturn(['data'=>'ok']);
      $courseService = new CourseService($mockUserCourseService);
      $response = $courseService->store($course);
      $this->assertEquals($response['message'], "Create successful!");
      $this->assertDatabaseHas('users',['email'=>'user1@gmail.com']);
    }

    public function testStoreMissingSubjectFieldFailed()
    {
        $user = User::insertGetId([
            'username' => 'david kushner',
            'fullname' => 'david kushner',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('password'),
            'status' => StatusTypeContants::ACTIVE,
            'role' => UserRoleContants::TEACHER
          ]);
          $course = [
            'name' => 'example courses',
            'descriptions' => 'this is descriptions for course',
            'owner_id' => $user,
            'status' => StatusTypeContants::ACTIVE
          ];
          $mockUserCourseService = Mockery::mock(UserCourseService::class);
          $mockUserCourseService->shouldReceive('store')->andReturn(['data'=>'ok']);
          $courseService = new CourseService($mockUserCourseService);
          $response = $courseService->store($course);
          $this->assertEquals($response['message'], "Error, please try again later!");
    }

    public function testStoreRollbackWhenCreateTeacherFailed()
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
      $mockUserCourseService = Mockery::mock(UserCourseService::class);
      $mockUserCourseService->shouldReceive('store')->andThrow(new Exception('error'));
      $courseService = new CourseService($mockUserCourseService);
      $response = $courseService->store($course);
      $this->assertEquals($response['message'], "Error, please try again later!");
    }
}

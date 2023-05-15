<?php

namespace Tests\Unit\Services\BaseServices;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use App\Services\CourseService;
use App\Services\SubjectService;
use App\Services\UserCourseService;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetAllTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetAllSuccess()
    {
        User::factory()->count(10)->create();
        Subject::factory()->count(10)->create();
        Course::factory()->count(10)->create();
        $users = User::all();
        $subjects = Subject::all();
        $courses = Course::all();

        $userService = new UserService();
        $subjectService = new SubjectService();
        $userCourseService = new UserCourseService();
        $courseService = new CourseService($userCourseService);

        $userResult = $userService->getAll();
        $subjectResult = $subjectService->getAll();
        $courseResult = $courseService->getAll();

        $this->assertEquals($users, $userResult);
        $this->assertEquals($subjects, $subjectResult);
        $this->assertEquals($courses, $courseResult);
    }
}

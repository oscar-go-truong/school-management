<?php

namespace Tests\Unit\Services\BaseServices;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use App\Services\CourseService;
use App\Services\SubjectService;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetByIdTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetByIdSuccess()
    {
        User::factory()->count(10)->create();
        Subject::factory()->count(10)->create();
        Course::factory()->count(10)->create();

        $user = User::all()->random();
        $subject = Subject::all()->random();
        $course = Course::all()->random();

        $userService = new UserService();
        $subjectService = new SubjectService();
        $courseService = new CourseService();

        $userResult = $userService->getById($user->id);
        $subjectResult = $subjectService->getById($subject->id);
        $courseResult = $courseService->getById($course->id);

        $this->assertEquals($user, $userResult);
        $this->assertEquals($subject, $subjectResult);
        $this->assertEquals($course, $courseResult);
    }
}

<?php

namespace Tests\Feature\Courses;

use App\Enums\UserRoleNameContants;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetDetailPageTest extends TestCase
{
    use RefreshDatabase;

    protected $teacher;
    protected $admin;
    protected $course;

    protected function setUp(): void
    {
        parent::setUp();
        $roles = [];
        foreach (UserRoleNameContants::getvalues() as $role) {
            $roles[] = [
            'name' => $role,
            'guard_name' => 'web'
            ];
        }
        Role::insert($roles);

        $this->admin = User::factory()->create();
        $this->admin->assignRole(UserRoleNameContants::ADMIN);

        $this->teacher = User::factory()->create();
        $this->teacher->assignRole(UserRoleNameContants::TEACHER);

        Subject::factory()->create();

        $this->course = Course::factory()->create();

        UserCourse::create([
            'user_id' => $this->teacher->id,
            'course_id' => $this->course->id
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminGetDetailPageSuccess()
    {

        $this->actingAs($this->admin);

        $response = $this->get('/courses/' . $this->course->id);
        $response->assertViewIs('course.detail');
        $response->assertStatus(200);
    }

    public function testUserInCourseGetDetailPageSuccess()
    {
        $this->actingAs($this->teacher);

        $response = $this->get('/courses/' . $this->course->id);
        $response->assertViewIs('course.detail');
        $response->assertStatus(200);
    }

    public function testUserNotInCourseGetDetailPageReturn404()
    {
        $user = User::factory()->create();
        $user->assignRole(UserRoleNameContants::STUDENT);
        $this->actingAs($user);

        $response = $this->get('/courses/' . $this->course->id);
        $response->assertStatus(404);
    }
}

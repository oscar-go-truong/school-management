<?php

namespace Tests\Feature\Courses;

use App\Enums\UserRoleNameContants;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetUpdatePageTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $teacher;
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
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminGetUpdatePageSuccess()
    {
        $this->actingAs($this->admin);

        $response = $this->get('courses/' . $this->course->id . '/edit');
        $response->assertViewIs('course.update');
        $response->assertStatus(200);
    }

    public function testUserGetUpdatePageReturn404()
    {
        $this->actingAs($this->teacher);

        $response = $this->get('courses/' . $this->course->id . '/edit');
        $response->assertStatus(404);
    }
}

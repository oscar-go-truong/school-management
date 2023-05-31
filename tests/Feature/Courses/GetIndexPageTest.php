<?php

namespace Tests\Feature\Courses;

use App\Enums\UserRoleNameContants;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetIndexPageTest extends TestCase
{
    use RefreshDatabase;

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

        Subject::factory()->create();

        $teacher = User::factory()->create();
        $teacher->assignRole(UserRoleNameContants::TEACHER);

        Course::factory()->count(30)->create();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetIndexPageSuccess()
    {
        $user = User::factory()->create();
        $user->assignRole(UserRoleNameContants::getRandomValue());

        $this->actingAs($user);
        $response = $this->get('courses/');
        $response->assertStatus(200);
        $response->assertViewIs('course.index');
    }
}

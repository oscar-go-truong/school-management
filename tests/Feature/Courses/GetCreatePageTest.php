<?php

namespace Tests\Feature\Courses;

use App\Enums\UserRoleNameContants;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetCreatePageTest extends TestCase
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
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminGetCreatePageSuccess()
    {
        User::factory()->count(20)->create();
        Subject::factory()->count(20)->create();

        $user = User::factory()->create();
        $user->assignRole(UserRoleNameContants::ADMIN);

        $this->actingAs($user);

        $response = $this->get('/courses/create');

        $response->assertViewIs('course.create');
        $response->assertStatus(200);
    }

    public function testUserGetCreatePageReturn404()
    {
        User::factory()->count(20)->create();
        Subject::factory()->count(20)->create();
        $user = User::factory()->create();
        do {
            $role = UserRoleNameContants::getRandomValue();
        } while ($role === UserRoleNameContants::ADMIN);
        $user->assignRole($role);
        $this->actingAs($user);

        $response = $this->get('/courses/create');
        $response->assertStatus(404);
    }
}

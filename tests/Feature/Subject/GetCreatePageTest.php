<?php

namespace Tests\Feature\Subject;

use App\Enums\UserRoleNameContants;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $user = User::factory()->create();
        $user->assignRole(UserRoleNameContants::ADMIN);

        $this->actingAs($user);
        $response = $this->get('/subjects/create');
        $response->assertViewIs('subject.create');
        $response->assertStatus(200);
    }

    public function testUserGetCreatePageReturn404()
    {
        $user = User::factory()->create();
        do {
            $role = UserRoleNameContants::getRandomValue();
        } while ($role === UserRoleNameContants::ADMIN);
        $user->assignRole($role);
        $this->actingAs($user);

        $response = $this->get('/subjects/create');
        $response->assertStatus(404);
    }
}

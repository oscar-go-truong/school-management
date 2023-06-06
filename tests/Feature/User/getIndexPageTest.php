<?php

namespace Tests\Feature\User;

use App\Enums\UserRoleNameContants;
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

        $users = User::factory()->count(20)->create();
        foreach ($users as $user) {
            $user->assignRole(UserRoleNameContants::getRandomValue());
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminGetViewIndexOfUsersManagementSuccess()
    {
        $user = User::factory()->create();
        $user->assignRole(UserRoleNameContants::ADMIN);

        $this->actingAs($user);

        $response = $this->get('users/');
        $response->assertStatus(200);
        $response->assertViewIs('user.index');
    }

    public function testUserGetViewIndexOfUsersManagementReturn404()
    {
        $user = User::factory()->create();
        do {
            $role = UserRoleNameContants::getRandomValue();
        } while ($role === UserRoleNameContants::ADMIN);
        $user->assignRole($role);

        $this->actingAs($user);

        $response = $this->get('users/');
        $response->assertStatus(404);
    }
}

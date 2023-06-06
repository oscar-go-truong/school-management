<?php

namespace Tests\Feature\User;

use App\Enums\UserRoleNameContants;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetUpdatePageTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

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

        $this->user = User::factory()->create();
        do {
            $role = UserRoleNameContants::getRandomValue();
        } while ($role === UserRoleNameContants::ADMIN);
        $this->user->assignRole($role);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminGetUpdateOfUsersManagementPageSuccess()
    {
        $this->actingAs($this->admin);

        $response = $this->get('users/' . $this->user->id . '/edit');

        $response->assertStatus(200);
        $response->assertViewIs('user.update');
    }

    public function testUserGetUpdateOfUsersManagementPageReturn404()
    {
        $this->actingAs($this->user);

        $response = $this->get('users/' . $this->user->id . '/edit');

        $response->assertStatus(404);
    }
}

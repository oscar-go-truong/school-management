<?php

namespace Tests\Feature\Authenticate;

use App\Enums\UserRoleNameContants;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
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
    public function testAuthenticatedResponse()
    {
        $user = User::factory()->create();
        $role = collect(UserRoleNameContants::getValues())->random();
        $user->assignRole($role);
        $this->actingAs($user);
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('auth.profile');
    }

    public function testNotAuthenticateRedirectToLoginView()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}

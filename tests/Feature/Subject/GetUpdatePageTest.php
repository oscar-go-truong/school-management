<?php

namespace Tests\Feature\Subject;

use App\Enums\UserRoleNameContants;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetUpdatePageTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $subject;
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

        $this->subject = Subject::factory()->create();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminGetUpdatePageSuccess()
    {
        $this->actingAs($this->admin);

        $response = $this->get('subjects/' . $this->subject->id . '/edit');

        $response->assertViewIs('subject.update');
        $response->assertStatus(200);
    }

    public function testUserGetUpdatePageReturn404()
    {
        $this->actingAs($this->user);

        $response = $this->get('subjects/' . $this->subject->id . '/edit');

        $response->assertStatus(404);
    }
}

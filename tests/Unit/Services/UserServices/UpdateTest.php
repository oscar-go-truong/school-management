<?php

namespace Tests\Unit\Services\UserServices;

use App\Enums\UserRoleNameContants;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
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

    public function testUpdateNewDataSuccess()
    {
        $user = User::factory()->create();
        $newRole = UserRoleNameContants::ADMIN;
        $userUpdate = [
            'username' => 'davidskusnerfans',
            'fullname' => 'david kushner fans',
            'email' => 'davidfanss@gmail.com',
            'role' => $newRole,
        ];

        $request = Request::create('/users/' . $user->id, 'PATCH', $userUpdate);

        $userService = app()->make(UserService::class);
        $userService->update($user->id, $request);
        $newUser = User::find($user->id);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => 'davidskusnerfans',
            'fullname' => 'david kushner fans',
            'email' => 'davidfanss@gmail.com',
        ]);
        $this->assertTrue($newUser->hasRole($newRole));
    }
}

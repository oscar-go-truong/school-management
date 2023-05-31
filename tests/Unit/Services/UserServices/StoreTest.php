<?php

namespace Tests\Unit\Services\UserServices;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleNameContants;
use App\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StoreTest extends TestCase
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
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testStoreSuccess()
    {
        $user = [
            'username' => 'david.ko',
            'fullname' => 'david kushner',
            'email' => 'david@gmail.com',
            'password' => Hash::make('password'),
            'status' => StatusTypeContants::ACTIVE,
            'role' => UserRoleNameContants::ADMIN
        ];
        $request = Request::create('/users', 'POST', $user);

        $userService = app()->make(UserService::class);

        $userService->store($request);

        $this->assertDatabaseHas('users', [
            'email' => 'david@gmail.com'
        ]);
    }

    public function testStoreDuplicateEmailFailed()
    {

        $user1 = [
            'username' => 'user-1',
            'fullname' => 'david kushner',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('password'),
            'status' => StatusTypeContants::ACTIVE,
            'role' => UserRoleNameContants::STUDENT
        ];

        $user2 = [
            'username' => 'user-2',
            'fullname' => 'david kushner',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('password'),
            'status' => StatusTypeContants::ACTIVE,
            'role' => UserRoleNameContants::TEACHER
        ];
        $userService = app()->make(UserService::class);

        $request = Request::create('/users', 'POST', $user1);
        $userService->store($request);
        $this->expectException(QueryException::class);
        $request = Request::create('/users', 'POST', $user2);
        $userService->store($request);
    }
}

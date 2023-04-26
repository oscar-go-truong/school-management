<?php

namespace Tests\Unit\Services\UserServices;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleContants;
use App\Services\UserService;
use ErrorException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;
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
            'role' => UserRoleContants::STUDENT
        ];
        $userService = new UserService();
        $userService->store($user);
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
            'role' => UserRoleContants::STUDENT
        ];

        $user2 = [
            'username' => 'user-2',
            'fullname' => 'david kushner',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('password'),
            'status' => StatusTypeContants::ACTIVE,
            'role' => UserRoleContants::STUDENT
        ];
        $userService = new UserService();
        $userService->store($user1);
        $this->expectException(QueryException::class);
        $userService->store($user2);
    }

    public function testStoreDuplicateUsernameFailed()
    {
    
        $user1 = [
            'username' => 'user-1',
            'fullname' => 'david kushner',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('password'),
            'status' => StatusTypeContants::ACTIVE,
            'role' => UserRoleContants::STUDENT
        ];

        $user2 = [
            'username' => 'user-1',
            'fullname' => 'david kushner',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('password'),
            'status' => StatusTypeContants::ACTIVE,
            'role' => UserRoleContants::STUDENT
        ];
        $userService = new UserService();
        $userService->store($user1);
        $this->expectException(QueryException::class);
        $userService->store($user2);
    }

    public function testStoreMissingPasswordFailed()
    {
    
        $user = [
            'username' => 'user-1',
            'fullname' => 'david kushner',
            'email' => 'user1@gmail.com',
            'status' => StatusTypeContants::ACTIVE,
            'role' => UserRoleContants::STUDENT
        ];

        $userService = new UserService();
        $this->expectException(ErrorException::class);
        $userService->store($user);
    }

}

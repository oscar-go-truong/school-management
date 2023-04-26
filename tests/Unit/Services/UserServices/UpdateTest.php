<?php

namespace Tests\Unit\Services\UserServices;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleContants;
use App\Models\User;
use App\Services\UserService;
use ErrorException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testUpdateNewDataSuccess() 
    {
        $user = User::factory()->create();
        $userUpdate = [
            'username' => 'davidskusnerfans',
            'fullname' => 'david kushner fans',
            'email' => 'davidfanss@gmail.com',
            'status' => StatusTypeContants::INACTIVE,
            'role' => UserRoleContants::ADMIN
        ];
        $userService = new UserService();
        $userService->update($user->id, $userUpdate);
        $newUser = User::find($user->id);
        $this->assertEquals($userUpdate['username'], $newUser->username);
        $this->assertEquals($userUpdate['fullname'], $newUser->fullname);
        $this->assertEquals($userUpdate['email'], $newUser->email);
        $this->assertEquals($userUpdate['status'], $newUser->status);
        $this->assertEquals($userUpdate['role'], $newUser->role);
    }

    public function testUpdateOldDataSuccess() 
    {
        $user = User::factory()->create();
        $userUpdate = [
            'username' => $user->username,
            'fullname' => $user->fullname,
            'email' => $user->email,
            'password' => $user->password,
            'status' => StatusTypeContants::INACTIVE,
            'role' => UserRoleContants::ADMIN
        ];
        $userService = new UserService();
        $userService->update($user->id, $userUpdate);
        $newUser = User::find($user->id);
        $this->assertEquals($userUpdate['username'], $newUser->username);
        $this->assertEquals($userUpdate['fullname'], $newUser->fullname);
        $this->assertEquals($userUpdate['email'], $newUser->email);
        $this->assertEquals($userUpdate['status'], $newUser->status);
        $this->assertEquals($userUpdate['role'], $newUser->role);
    }

    public function testUpdateDuplicateEmailFailed() 
    {
        User::insert([
            'username' => 'davidskusnerfans',
            'fullname' => 'david kushner fans',
            'password' => Hash::make('password'),
            'email' => 'david@gmail.com',
            'status' => StatusTypeContants::INACTIVE,
            'role' => UserRoleContants::ADMIN
        ]);
        $user = User::factory()->create();
        $userUpdate = [
            'username' => 'username-1',
            'fullname' => 'peter pan',
            'email' => 'david@gmail.com',
            'status' => StatusTypeContants::INACTIVE,
            'role' => UserRoleContants::ADMIN
        ];
        $userService = new UserService();
        $this->expectException(QueryException::class);
        $userService->update($user->id, $userUpdate);
    }

    public function testUpdateDuplicateUsernameFailed() 
    {
        User::insert([
            'username' => 'davidskusnerfans',
            'fullname' => 'david kushner fans',
            'password' => Hash::make('password'),
            'email' => 'david@gmail.com',
            'status' => StatusTypeContants::INACTIVE,
            'role' => UserRoleContants::ADMIN
        ]);
        $user = User::factory()->create();
        $userUpdate = [
            'username' => 'davidskusnerfans',
            'fullname' => 'peter pan',
            'email' => 'exmaple@gmail.com',
            'status' => StatusTypeContants::INACTIVE,
            'role' => UserRoleContants::ADMIN
        ];
        $userService = new UserService();
        $this->expectException(QueryException::class);
        $userService->update($user->id, $userUpdate);
    }
}

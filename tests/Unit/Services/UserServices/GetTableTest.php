<?php

namespace Tests\Unit\Services\UserServices;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleNameContants;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetTableTest extends TestCase
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

    protected function Standardized($queryResult)
    {
        $result = $queryResult;
        $result = [
        'data' => $result->items(),
        'from' => $result->firstItem(),
        'to' => $result->lastItem(),
        'last_page' => $result->lastPage(),
        'total' => $result->total()
        ];
        $data = collect($result['data'])->map(function ($user) {
            return [
            'id' => $user->id,
            'username' => $user->username,
            'fullname' => $user->fullname,
            'email' => $user->email,
            'role' => $user->getRoleNames()->first(),
            'status' => $user->status
            ];
        });
        $result['data'] = $data;

        return $result;
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */

     /**
    * @dataProvider getTableProvider
     */
    public function testAdminGetTableSuccess($input, $queryExpected)
    {
        $admin = User::factory()->create();
        $admin->assignRole(UserRoleNameContants::ADMIN);

        $this->actingAs($admin);

        $userService = app()->make(UserService::class);

        $expected = $this->Standardized($queryExpected());
        $result = $userService->getTable($input);
        $this->assertEquals($expected, $result);
    }

    public function getTableProvider()
    {
        $role = [UserRoleNameContants::getRandomValue(),UserRoleNameContants::getRandomValue()];
        return [
        'empty' => [
           (object) [

            ],
            function () {
                return User::paginate(10);
            }
        ],
        'limit' => [
        (object) [
            'limit' => 10
        ],
        function () {
            return User::paginate(10);
        }
        ],
        'limit & search' => [
        (object)   [
            'limit' => 10,
            'search' => [
                'column' => 'email',
                'type' => 'like',
                'key' => 'example'
            ]
        ],
        function () {
            return User::where('email', 'like', '%example%')->paginate(10);
        }
        ],
        'limit, order & search' => [
        (object)   [
            'limit' => 10,
            'search' => [
                'column' => 'email',
                'type' => 'like',
                'key' => 'example'
            ],
            'orderBy' => 'email',
            'orderDirect' => 'desc'
        ],
        function () {
            return User::where('email', 'like', '%example%')->orderBy('email', 'desc')->paginate(10);
        }
        ],
        'limit, order, filter role & search' => [
        (object)  [
            'limit' => 10,
            'search' => [
                'column' => 'email',
                'type' => 'like',
                'key' => 'example'
            ],
            'orderBy' => 'email',
            'orderDirect' => 'desc',
            'role' => $role
        ],
        function () use ($role) {
            return User::where('email', 'like', '%example%')
            ->orderBy('email', 'desc')
            ->whereHas('roles', function ($query) use ($role) {
                $query->whereIn('name', $role);
            })
            ->paginate(10);
        }
        ],
        'limit, order, filter role, filter status & search' => [
        (object) [
            'limit' => 10,
            'search' => [
                'column' => 'email',
                'type' => 'like',
                'key' => 'example'
            ],
            'orderBy' => 'email',
            'orderDirect' => 'desc',
            'role' => $role,
            'status' => StatusTypeContants::ACTIVE
        ],
        function () use ($role) {
            return User::where('email', 'like', '%example%')
                        ->orderBy('email', 'desc')
                        ->whereHas('roles', function ($query) use ($role) {
                            $query->whereIn('name', $role);
                        })
                        ->where('status', StatusTypeContants::ACTIVE)
                        ->paginate(10);
        }
        ],
        ];
    }
}

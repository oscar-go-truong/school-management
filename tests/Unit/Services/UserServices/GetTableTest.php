<?php

namespace Tests\Unit\Services\UserServices;

use App\Enums\UserRoleContants;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTableTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

     /**
    * @dataProvider getTableProvider
     */
    public function testGetTableSuccess($input, $query)
    {
      User::factory()->count(20)->create();
      $expected = $query();
      $userService = new UserService();
       foreach($expected as $user) 
       {
           $user->role = UserRoleContants::getKey($user->role);
       }
       $result = $userService->getTable($input);
       $this->assertEquals($result,$expected);
      
   }

   public function getTableProvider(){
    return [
        'empty'=>[
            [
                
            ],
            function () {
                return User::paginate(10);
            }
        ],
        'limit'=>[
            [
                'limit' => 10
            ],
            function () {
                return User::paginate(10);
            }
        ],
        'limit & search'=>[
            [
                'limit' => 10,
                'search' => [
                    'column'=>'email',
                    'type' => 'like',
                    'key' => 'example'
                ]
            ],
            function () {
                return User::where('email','like','%example%')->paginate(10);
            }
        ],
        'limit, order & search'=> [
            [
                'limit' => 10,
                'search' => [
                    'column'=>'email',
                    'type' => 'like',
                    'key' => 'example'
                ],
                'orderBy' => 'email',
                'orderDirect' => 'desc'
            ],
            function () {
                return User::where('email','like','%example%')->orderBy('email','desc')->paginate(10);
            }
        ],
        'limit, order, filter role & search'=> [
            [
                'limit' => 10,
                'search' => [
                    'column'=>'email',
                    'type' => 'like',
                    'key' => 'example'
                ],
                'orderBy' => 'email',
                'orderDirect' => 'desc',
                'role'=>[1,2]
            ],
            function () {
                return User::where('email','like','%example%')->orderBy('email','desc')->whereIn('role', [1,2])->paginate(10);
            }
        ],
        'limit, order, filter role, filter status & search'=> [
            [
                'limit' => 10,
                'search' => [
                    'column'=>'email',
                    'type' => 'like',
                    'key' => 'example'
                ],
                'orderBy' => 'email',
                'orderDirect' => 'desc',
                'role'=>[1,2],
                'status' => 0
            ],
            function () {
                return User::where('email','like','%example%')->orderBy('email','desc')->whereIn('role', [1,2])->where('status',0)->paginate(10);
            }
        ],
    ];
}
}

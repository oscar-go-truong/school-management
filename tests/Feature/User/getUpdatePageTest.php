<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetUpdatePageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testViewUpdateOfUsersManagementSuccess()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('users/'.$user->id.'/edit');
        $response->assertStatus(200);
        $response->assertViewIs('user.update');
    }
}

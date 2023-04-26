<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class getCreatePageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testViewCreateOfUsersManagementSuccess()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('users/create');
        $response->assertStatus(200);
        $response->assertViewIs('user.create');
    }
}

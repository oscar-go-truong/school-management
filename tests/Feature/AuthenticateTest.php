<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAuthenticatedResponse()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/');
        $response->assertStatus(200); 
        $response->assertViewIs('user.profile'); 
    }

    public function testNotAuthenticateRedirectToLoginView()
    {
        $response = $this->get('/');
        $response->assertStatus(302); 
        $response->assertRedirect('/login'); 
    }
}

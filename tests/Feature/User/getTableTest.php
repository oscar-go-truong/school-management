<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class GetTableTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetTableSuccess()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        User::factory()->count(20)->create();
        $response = $this->get('/users/table');
        $response->assertStatus(200);
    }
}

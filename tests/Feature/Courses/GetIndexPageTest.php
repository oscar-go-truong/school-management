<?php

namespace Tests\Feature\Courses;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetIndexPageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetIndexPageSuccess()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('courses/');
        $response->assertStatus(200);
        $response->assertViewIs('course.index');
    }
}

<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetCreatePageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetCreatePageSuccess()
    {
        User::factory()->count(20)->create();
        Subject::factory()->count(20)->create();
        $course = Course::factory()->count(20)->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('courses/create');
        $response->assertViewIs('course.create');
        $response->assertStatus(200);
    }
}

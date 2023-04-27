<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetDetailPageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetDetailPageSuccess()
    {
        User::factory()->count(20)->create();
        Subject::factory()->count(20)->create();
        $course = Course::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('courses/'.$course->id);
        $response->assertViewIs('course.detail');
        $response->assertStatus(200);
    }
}

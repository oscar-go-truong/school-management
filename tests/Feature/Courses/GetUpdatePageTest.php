<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetUpdatePageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetUpdatePageSuccess()
    {
        User::factory()->count(20)->create();
        Subject::factory()->count(20)->create();
        $course = Course::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('courses/'.$course->id.'/edit');
        $response->assertViewIs('course.update');
        $response->assertStatus(200);
    }
}

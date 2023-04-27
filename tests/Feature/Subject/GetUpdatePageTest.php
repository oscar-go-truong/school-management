<?php

namespace Tests\Feature\Subject;

use App\Models\Subject;
use App\Models\User;
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
    public function testUpdatePageSuccess()
    {
        $user  = User::factory()->create();
        $this->actingAs($user);
        $subject = Subject::factory()->create();
        $response = $this->get('subjects/'.$subject->id.'/edit');
        $response->assertViewIs('subject.update');
        $response->assertStatus(200);
    }
}

<?php

namespace Tests\Feature\Subject;

use App\Enums\UserRoleNameContants;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetDetailPageTest extends TestCase
{
    use RefreshDatabase;

    protected $subject;
    protected $admin;
    protected $userLearnSubject;

    protected function setUp(): void
    {
        parent::setUp();

        $roles = [];
        foreach (UserRoleNameContants::getvalues() as $role) {
            $roles[] = [
            'name' => $role,
            'guard_name' => 'web'
            ];
        }
        Role::insert($roles);

        $this->admin = User::factory()->create();
        $this->admin->assignRole(UserRoleNameContants::ADMIN);

        $this->subject = Subject::factory()->create();

        $this->userLearnSubject = User::factory()->create();
        $this->userLearnSubject->assignRole(UserRoleNameContants::TEACHER);

        $courseOfSubject = Course::factory()->create();

        UserCourse::create([
            'user_id' => $this->userLearnSubject->id,
            'course_id' => $courseOfSubject->id
        ]);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminGetDetailPageSuccess()
    {

        $this->actingAs($this->admin);

        $response = $this->get('/subjects/' . $this->subject->id);
        $response->assertViewIs('subject.detail');
        $response->assertStatus(200);
    }

    public function testUserLearnSubjectGetDetailPageSuccess()
    {
        $this->actingAs($this->userLearnSubject);

        $response = $this->get('/subjects/' . $this->subject->id);
        $response->assertViewIs('subject.detail');
        $response->assertStatus(200);
    }

    public function testUserDoesntLearnSubjectGetDetailPageReturn404()
    {
        $user = User::factory()->create();
        $user->assignRole(UserRoleNameContants::STUDENT);
        $this->actingAs($user);

        $response = $this->get('/subjects/' . $this->subject->id);
        $response->assertStatus(404);
    }
}

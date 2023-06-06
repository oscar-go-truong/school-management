<?php

namespace Tests\Unit\Services\CourseService;

use App\Enums\TimeConstants;
use App\Enums\UserRoleNameContants;
use App\Models\Course;
use App\Models\Room;
use App\Models\Subject;
use App\Models\User;
use App\Services\CourseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

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
    }
    public function testStoreSuccess()
    {
        $teacher = User::factory()->create();
        $teacher->assignRole(UserRoleNameContants::TEACHER);

        $room = Room::factory()->create();
        $weekday = collect(TimeConstants::WEEKDAY)->random();
        $schedules = json_encode([
          (object)[
            'start_time' => "15:30:00",
            'finish_time' => "17:00:00",
            'weekday' => $weekday,
            'room' => $room->id
          ]
        ]);

        $subject = Subject::factory()->create();
        $courseId = Course::factory()->create()->id;
        $course = [
          'owner_id' => $teacher->id,
          'subject_id' => $subject->id,
          'schedules' => $schedules,
          'name' => 'course test',
          'descriptions' => 'somethings'
        ];

        $request = Request::create('/courses', 'POST', $course);

        $courseService = app()->make(CourseService::class);

        $course = $courseService->update($courseId, $request)['data'];

        $this->assertDatabaseHas('courses', [
          'id' => $courseId,
          'owner_id' => $teacher->id,
          'subject_id' => $subject->id,
          'name' => 'course test',
          'descriptions' => 'somethings'
        ]);

        $this->assertDatabaseHas('user_course', [
          'user_id' => $teacher->id,
          'course_id' => $course->id
        ]);

        $this->assertDatabaseHas('schedules', [
          'start_time' => "15:30:00",
          'finish_time' => "17:00:00",
          'weekday' => $weekday,
          'room_id' => $room->id,
          'course_id' => $course->id
        ]);
    }
}

<?php

namespace Tests\Unit\Services\CourseService;

use App\Enums\UserRoleNameContants;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use App\Services\CourseService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetTableTest extends TestCase
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

        $teacher = User::factory()->create();
        $teacher->assignRole(UserRoleNameContants::TEACHER);

        Subject::factory()->create();

        Course::factory()->count(20)->create();
    }

    protected function Standardized($queryResult)
    {
        $result = $queryResult;
        $result = [
            'data' => $result->items(),
            'from' => $result->firstItem(),
            'to' => $result->lastItem(),
            'last_page' => $result->lastPage(),
            'total' => $result->total()
        ];
        $courses = $result['data'];

        $data = array_map(function ($course) {
            $schedules = $course->schedules->map(function ($schedule) {
                return [
                'start' => $schedule->start_time,
                'end' => $schedule->finish_time,
                'weekday' => $schedule->weekday,
                'room' => $schedule->room->name
                ];
            });

            return [
            'id' => $course->id,
            'name' => $course->name,
            'subject' => $course->subject->name,
            'schedules' => $schedules,
            'year' => Carbon::parse($course->created_at)->year,
            'status' => $course->status,
            'teachersCount' => $course->teachers_count,
            'studentsCount' => $course->students_count,
            'examsCount' => $course->exams_count,
            ];
        }, $courses);

        $result['data'] = $data;

        return $result;
    }

    /**
    * @dataProvider getTableProvider
     */
    public function testAdminGetTableSuccess($input, $queryExpected)
    {
        $admin = User::factory()->create();
        $admin->assignRole(UserRoleNameContants::ADMIN);

        $this->actingAs($admin);

        $expected = $this->Standardized($queryExpected());


        $courseService = app()->make(CourseService::class);
        $result = $courseService->getTable($input);

        $this->assertEquals($result, $expected);
    }

    public function getTableProvider()
    {
        return [
        'empty' => [
            (object)[
            ],
            function () {
                return Course::with('homeroomTeacher')
                ->withCount('exams')
                ->withCount('teachers')
                ->withCount('students')
                ->with('subject')
                ->paginate(10);
            }
        ],
        'limit' => [
            (object)[
                'limit' => 10
            ],
            function () {
                return Course::with('homeroomTeacher')
                ->withCount('exams')
                ->withCount('teachers')
                ->withCount('students')
                ->with('subject')
                ->paginate(10);
            }
        ],
        'limit, order' => [
            (object)[
                'limit' => 10,
                'orderBy' => 'name',
                'orderDirect' => 'desc'
            ],
            function () {
                return Course::orderBy('name', 'desc')
                ->with('homeroomTeacher')
                ->withCount('exams')
                ->withCount('teachers')
                ->withCount('students')
                ->with('subject')
                ->paginate(10);
            }
        ],
        'limit, order & filter by subject id' => [
            (object)[
                'limit' => 10,
                'orderBy' => 'name',
                'orderDirect' => 'desc',
                'subjectId' => 1
            ],
            function () {
                return Course::where('subject_id', 1)
                ->orderBy('name', 'desc')
                ->with('homeroomTeacher')
                ->withCount('exams')
                ->withCount('teachers')
                ->withCount('students')
                ->with('subject')
                ->paginate(10);
            }
        ],
        ];
    }
}

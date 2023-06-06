<?php

namespace Tests\Unit\Services\SubjectService;

use App\Enums\UserRoleNameContants;
use App\Models\Subject;
use App\Models\User;
use App\Services\SubjectService;
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

        Subject::factory()->count(20)->create();
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
        $subjects = $result['data'];
        $data = [];
        foreach ($subjects as $subject) {
            $data[] = [
                'id' => $subject->id,
                'name' => $subject->name,
                'descriptions' => $subject->descriptions,
                'status' => $subject->status,
                'course_count' => $subject->course_count
            ];
        }
        $result['data'] = $data;

        return $result;
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    /** @dataProvider getTableProvider
     */

    public function testAdminGetTableSuccess($input, $queryExpected)
    {
        $admin = User::factory()->create();
        $admin->assignRole(UserRoleNameContants::ADMIN);

        $this->actingAs($admin);

        $subjectService = app()->make(SubjectService::class);

        $expected = $this->Standardized($queryExpected());
        $result = $subjectService->getTable($input);
        $this->assertEquals($result, $expected);
    }

    public function getTableProvider()
    {
        return [
        'empty' => [
           (object) [

            ],
            function () {
                return Subject::withCount('course')->paginate(10);
            }
        ],
        'limit' => [
           (object) [
                'limit' => 10
            ],
            function () {
                return Subject::withCount('course')->paginate(10);
            }
        ],
        'limit, order' => [
          (object)  [
                'limit' => 10,
                'orderBy' => 'name',
                'orderDirect' => 'desc'
            ],
            function () {
                return Subject::withCount('course')->orderBy('name', 'desc')->paginate(10);
            }
        ]
        ];
    }
}

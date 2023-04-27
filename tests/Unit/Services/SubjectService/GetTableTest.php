<?php

namespace Tests\Unit\Services\SubjectService;

use App\Models\Subject;
use App\Models\User;
use App\Services\SubjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTableTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    /** @dataProvider getTableProvider
     */

    public function testGetTableSuccess($input, $queryExpected)
    {
      $subject = Subject::factory()->count(20)->create();
      
      $expected = $queryExpected();
      $subjectService = new SubjectService();
      $result = $subjectService->getTable($input);
      $this->assertEquals($result,$expected);
      
   }

   public function getTableProvider(){
    return [
        'empty'=>[
            [
                
            ],
            function () {
                return Subject::withCount('course')->paginate(10);
            }
        ],
        'limit'=>[
            [
                'limit' => 10
            ],
            function () {
                return Subject::withCount('course')->paginate(10);
            }
        ],
        'limit, order'=> [
            [
                'limit' => 10,
                'orderBy' => 'name',
                'orderDirect' => 'desc'
            ],
            function () {
                return Subject::orderBy('name','desc')->withCount('course')->paginate(10);
            }
        ]
    ];
}
}

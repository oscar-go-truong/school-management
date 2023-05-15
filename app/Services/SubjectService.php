<?php

namespace App\Services;

use App\Enums\UserRoleNameContants;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class SubjectService extends BaseService
{
    public function getModel()
    {
        return Subject::class;
    }

    public function getTable($input)
    {
        $user = Auth::user();
        $query = $this->model;
        if(!$user->hasRole(UserRoleNameContants::ADMIN))
         $query = $query->whereHas('course.userCourse', function($query) use ($user) {
            $query->where('user_id', $user->id);
         })->withCount(['course' => function($query) use ($user){
            $query->whereHas('userCourse',function($query) use ($user){
                $query = $query->where('user_id',$user->id);
            });
        }]);
        else 
        $query = $this->model->withCount('course');
        $subjects = $this->orderNSearch($input, $query);
        return $subjects;
    }
}

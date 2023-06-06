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

    public function getTable($request)
    {
        $user = Auth::user();
        $query = $this->model;
        if (!$user->hasRole(UserRoleNameContants::ADMIN))
            $query = $query->whereHas('course.userCourses', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->withCount([
                    'course' => function ($query) use ($user) {
                        $query->whereHas('userCourses', function ($query) use ($user) {
                            $query = $query->where('user_id', $user->id);
                        });
                    }
                ]);
        else
            $query = $this->model->withCount('course');
        $result = $this->orderNSearch($request, $query);
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

    public function getAllSubjectsOfUser()
    {
        $user = Auth::user();
        $query = $this->model->select('id', 'name');
        if (!$user->hasRole(UserRoleNameContants::ADMIN))
            $query = $query->whereHas('course.userCourses', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        return $query->get();
    }
    public function update($id, $request)
    {
        $subject = ['name' => $request->name, 'descriptions' => $request->descriptions];
        return parent::update($id, $subject);
    }

    public function getById($id)
    {
        return $this->model->select('id', 'name', 'descriptions', 'created_at as date', 'status')->find($id);
    }

    public function getAll()
    {
        return $this->model->select('id', 'name')->get();
    }
}
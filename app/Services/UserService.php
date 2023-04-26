<?php

namespace App\Services;

use App\Enums\UserRoleContants;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function getModel()
    {
        return User::class;
    }

    public function getTable($input)
    {
        $query = $this->model;
        $query = $query->status($input)->role($input);

        $users = $this->orderNSearch($input, $query);
        foreach ($users as $user) {
            $user->role = UserRoleContants::getKey($user->role);
        }
        return $users;
    }

    public function store($user)
    {
        $user['password'] = Hash::make($user['password']);
        parent::store($user);
    }


    public function update($id, $user)
    {
        if (array_key_exists("password", $user)) {
            $user['password'] = Hash::make($user['password']);
        }
        parent::update($id, $user);
    }

    public function getUserCanJoinToCourseByRole($courseId, $role) {
        $joinedUserId = UserCourse::select('user_id')->where('course_id', $courseId)->get();
        $users = $this->model->whereNotIn('id', $joinedUserId)->where('role',$role)->get();
        return $users;
    }
}

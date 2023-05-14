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
        $query = $query->status($input)->inRole($input);
        $users = $this->orderNSearch($input, $query);
        foreach($users as $user)
            $user->role = $user->getRoleNames()[0];
        return $users;
    }

    public function store($data)
    {
        $data['password'] = Hash::make($data['password']);
        $user =  $this->model->create($data);
        if ($user) 
            {
                $user->assignRole($data['role']);
                return ['data'=> $user, 'message'=>"Create successful!"];
            }
        return  ['data'=> null, 'message'=>"Error, please try again later!"];
    }


    public function update($id, $data)
    {
        $user = $this->model->find($id);
        $oldRole = $user->getRoleNames();
        if(count($oldRole)!=0)
            $user->roles()->detach();
        $user->assignRole($data['role']);
        unset($data['role']);
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $result = $this->model->where('id', $id)->update($data);
            if ($result) 
                return ['data'=> $this->model->find($id), 'message'=>"Update successful!"];
            return  ['data'=> null, 'message'=>"Error, please try again later!"];
    }

    public function getUserCanJoinToCourseByRole($courseId, $role) {
        $joinedUserId = UserCourse::select('user_id')->where('course_id', $courseId)->get();
        $users = $this->model->whereNotIn('id', $joinedUserId)->role($role)->get();
        return $users;
    }

    public function getByRole($role){
        return $this->model->role($role);
    }
}

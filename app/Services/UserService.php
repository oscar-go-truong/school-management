<?php

namespace App\Services;

use App\Helpers\Message;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function getModel()
    {
        return User::class;
    }

    public function getTable($request)
    {
        $query = $this->model->status($request)->inRole($request);
        $result = $this->orderNSearch($request, $query);
        $data = collect($result['data'])->map(function ($user) {
            return [
            'id' => $user->id,
            'username' => $user->username,
            'fullname' => $user->fullname,
            'email' => $user->email,
            'role' => $user->getRoleNames()->first(),
            'status' => $user->status
            ];
        });
        $result['data'] = $data;
        return $result;
    }

    public function store($request)
    {
        $data = $request->input();
        $data['password'] = Hash::make($data['password']);
        $user = $this->model->create($data);
        if ($user) {
            $user->assignRole($data['role']);
            return ['data' => ['id' => $user->id], 'message' => Message::createSuccessfully('user')];
        }
        return ['data' => null, 'message' => Message::error()];
    }


    public function update($id, $request)
    {
        $user = $this->model->find($id);
        if (!$user) {
            return ['data' => null, 'message' => Message::error()];
        }

        $data = $request->only(['email', 'username', 'fullname', 'phone', 'mobile', 'address']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $oldRole = $user->getRoleNames();
        if (!empty($oldRole)) {
            $user->roles()->detach();
        }
        $user->assignRole($request->role);

        $result = $user->update($data);
        if ($result) {
            return ['data' => ['id' => $id], 'message' => Message::updateSuccessfully('user')];
        }

        return ['data' => null, 'message' => Message::error()];
    }

    public function getUserCanJoinToCourseByRole($courseId, $role)
    {
        $joinedUserId = UserCourse::select('user_id')->where('course_id', $courseId)->get();
        $users = $this->model->select('id', 'fullname', 'email')->whereNotIn('id', $joinedUserId)->role($role)->get();
        return $users;
    }

    public function getByRole($role)
    {
        return $this->model->select('id', 'fullname', 'email')->role($role)->get();
    }

    public function updateProfile($request)
    {
        $user = Auth::user();
        $resp = $this->model->where('id', $user->id)->update($request->only('fullname', 'phone', 'mobile', 'address'));
        return $resp;
    }
}

<?php
namespace App\Services;

use App\Models\User;

class UserService {
    public function table() {
        $users = User::paginate(15);
        return $users;
    }

    public function changeStatus($id, $status) {
        $user = User::where('id', $id)->update(['status' => $status]);
        return $user;
    }
}

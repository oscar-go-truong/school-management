<?php
namespace App\Services;

use App\Models\User;

class UserService {

    protected $user; 
    public function __construct(User $user){
        $this->user = $user;
    }
    public function table() {
        return $this->user->orderBy('created_at',"DESC")->paginate(15);
    }

    public function changeStatus($id, $status) {
        return  $this->user->where('id', $id)->update(['status' => $status]);
    }

    public function delete($id) {
        return $this->user->destroy($id);
    }

    public function storeCreate($user){
        return $this->user->create($user);
    }

    public function getUserById($id) {
        return $this->user->where('id', $id)->first();
    }

    public function storeUpdate($id, $user) {
        return  $this->user->where('id', $id)->update($user);
    }
}

<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService  extends BaseService{

   
    public function __construct(User $user){
        $this->model = $user;
    }

    public function changeStatus($id, $status) {
        return  $this->model->where('id', $id)->update(['status' => $status]);
    }


    public function store($user){
        $user['password'] = Hash::make($user['password']);
        parent::store($user);
    }


    public function update($id, $user) {
        if(array_key_exists("password", $user))
            $user['password'] = Hash::make($user['password']);
       parent::update($id,$user);
    }
}

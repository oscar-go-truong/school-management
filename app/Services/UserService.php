<?php
namespace App\Services;

use App\Enums\PaginationContants;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService  extends BaseService{

    public function getModel(){
        return User::class;
    }

    public function getTable($request){
        $limit = $request->query('limit',PaginationContants::LIMIT);
        $query = $this->model;
        $query = parent::orderNSearch($request, $query);
       
        $isFilterStatus = array_key_exists('status', $request->query()) && $request->query('status') !== null;
        if($isFilterStatus)
            {
                $status = $request->query('status');
                $query = $query->status($status);
            }
        $isFilterRole = array_key_exists('role', $request->query()) && count($request->query('role')) !== 0;
        if($isFilterRole)
                {
                    $role = $request->query('role');
                    $query = $query->role($role);
                }
        $users = $query->paginate($limit);
        
        foreach($users as $user) {
            $user->role = UserRole::getKey($user->role);
        }

        return $users;
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

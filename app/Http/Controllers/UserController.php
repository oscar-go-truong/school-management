<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $UserService;

    public function __construct(UserService $UserService)
    {
        $this->UserService = $UserService;
    } 

    // Render profile view.
    public function profile() {
        return view('user.profile',['user'=>Auth::User()]);
    }

    // Render all user 
    public function table() {
        $users = $this->UserService->table();
        return view('user.table', ['users'=> $users, 'role'=>UserRole::asArray()]);
    }
    // Handle update user's status
    public function changeStatus(Request $request,int $id){
        $status = $request->status;
        $user = $this->UserService->changeStatus($id, $status);
        return $user;
    }  
    // Handle delete user 
    public function delete(int $id){
        $user = $this->UserService->delete($id);
        return $user;
    } 
    // Render create user form
    public function viewCreate(){   
        return view('user.create', ['role'=>UserRole::asArray()]);
    }
    // Store user
    public function storeCreate(CreateUserRequest $request) {
       $this->UserService->storeCreate($request->input());
       return redirect('/users');
    }
    // Render update user form
    public function viewUpdate(int $id){   
        $user = $this->UserService->getUserById($id);
        return view('user.update', ['role'=>UserRole::asArray(), 'user'=>$user]);
    }
    // Store update
    public function storeUpdate(UpdateUserRequest $request, int $id) {
       
        $user = $this->UserService->getUserById(($id));
        if($user->email !== $request->email)
        { 
            $request->validate([
                'email' => 'unique:users,email',
            ]);
        }
        if($user->username !== $request->username)
        { 
            $request->validate([
                'username' => 'unique:users,username',
            ]);
        }
        $data = array('email'=>$request->email, 'username'=>$request->username, 'role'=>$request->role, 'fullname'=>$request->fullname);
        if($request->password)
            $data['password'] = $request->password;
        $this->UserService->storeUpdate($id, $data);
        return redirect('/users');
    }
}

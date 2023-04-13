<?php

namespace App\Http\Controllers;

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
    public function profile(Request $request) {
        return view('user.profile',['user'=>Auth::User()]);
    }

    // Render all user 
    public function table(Request $request) {
        $users = $this->UserService->table();
        return view('user.table', ['users'=> $users]);
    }
    // Handle update user's status
    public function changeStatus(Request $request,int $id){
        $status = $request->status;
        $user = $this->UserService->changeStatus($id, $status);
        return $user;
    }  
    // Handle delete user 
    public function delete(Request $request,int $id){
        $user = $this->UserService->delete($id);
        return $user;
    } 
}

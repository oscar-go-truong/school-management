<?php

namespace App\Http\Controllers;

use App\Enums\SearchColumnContants;
use App\Enums\StatusTypeContants;
use App\Enums\UserRoleContants;
use App\Http\Requests\CreateUpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Render profile view.
    public function profile()
    {
        return view('user.profile', ['user' => Auth::User()]);
    }

    // Render all user
    public function index()
    {
        $role = UserRoleContants::asArray();
        $status = StatusTypeContants::asArray();
        $searchColumns = SearchColumnContants::USER;
        return view('user.index', compact('role', 'status', 'searchColumns'));
    }
    // Get table data
    public function getTable(Request $request)
    {
        $table = $this->userService->getTable($request);
        return response()->json($table);
    }
    // Handle update user's status
    public function changeStatus(Request $request, int $id)
    {
        $status = $request->status;
        $user = $this->userService->changeStatus($id, $status);
        if ($user !== null) {
            return response()->json(['data' => $user, 'message' => "Update successful!"]);
        } else {
            return response()->json(['data' => null,'message' => "Error, Please try again later!"]);
        }
    }
    // Handle delete user
    public function destroy(int $id)
    {
        $user = $this->userService->destroy($id);
        if ($user !== null) 
            return response()->json(['data' => $user, 'message' => "Delete successful!"]);
        return response()->json(['data' => null,'message' => "Error, Please try again later!"]);
    }
    // Render create user form
    public function create()
    {
        return view('user.create', ['role' => UserRoleContants::asArray()]);
    }
    // Store user
    public function store(CreateUpdateUserRequest $request)
    {
        $this->userService->store($request->input());
        return redirect('/users');
    }
    // Render update user form
    public function edit(int $id)
    {
        $user = $this->userService->getById($id);
        if ($user) 
            return view('user.update', ['role' => UserRoleContants::asArray(), 'user' => $user]);
       return redirect()->back()->with('error', "User was deleted!");
        
    }
    // Store update
    public function update(CreateUpdateUserRequest $request, int $id)
    {
        $user = array('email' => $request->email, 'username' => $request->username, 'role' => $request->role, 'fullname' => $request->fullname);
        if ($request->password) {
            $user['password'] = $request->password;
        }
        $this->userService->update($id, $user);
        return redirect('/users');
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\PaginationContants;
use App\Enums\StatusType;
use App\Enums\UserRole;
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
    public function index(Request $request)
    {
        $users = $this->userService->getTable($request);
        return view('user.table', ['users' => $users, 'role' => UserRole::asArray(), 'status' => StatusType::asArray(), 'itemPerPageOptions' => PaginationContants::ITEM_PER_PAGE_OPTIONS]);
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
        return $user;
    }
    // Handle delete user
    public function destroy(int $id)
    {
        $user = $this->userService->destroy($id);
        return $user;
    }
    // Render create user form
    public function create()
    {
        return view('user.create', ['role' => UserRole::asArray()]);
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
        if ($user) {
            return view('user.update', ['role' => UserRole::asArray(), 'user' => $user]);
        } else {
            return redirect()->back()->with('error', "User was deleted!");
        }
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

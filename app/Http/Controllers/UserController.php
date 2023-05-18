<?php

namespace App\Http\Controllers;

use App\Enums\SearchColumnContants;
use App\Enums\StatusTypeContants;
use App\Http\Requests\CreateUpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

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
        $user =$this->userService->getById(Auth::User()->id);;
        return view('auth.profile', compact('user'));
    }

    // Render all user
    public function index()
    {
        $roles = Role::all();
        $status = StatusTypeContants::asArray();
        $searchColumns = SearchColumnContants::USER;
        return view('user.index', compact('roles', 'status', 'searchColumns'));
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
        $resp = $this->userService->changeStatus($id, $request);
        return response()->json($resp);
    }
    // Handle delete user
    public function destroy(int $id) : JsonResponse
    {
        $resp = $this->userService->destroy($id);
        return response()->json($resp);
    }
    // Render create user form
    public function create()
    {
        return view('user.create', ['roles' => Role::all()]);
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
        $roles = Role::all();
        if ($user) 
            return view('user.update', compact('roles','user'));
       return abort(404);
        
    }
    // Store update
    public function update(CreateUpdateUserRequest $request, int $id)
    {
        $this->userService->update($id, $request);
        return redirect('/users');
    }
}

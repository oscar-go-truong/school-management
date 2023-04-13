<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Render profile view.
    public function profile(Request $request) {
        return view('user.profile',['user'=>Auth::User()]);
    }
}

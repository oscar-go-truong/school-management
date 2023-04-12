<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Render profile view.
    public function profile(Request $request) {
        return view('user.profile');
    }
}

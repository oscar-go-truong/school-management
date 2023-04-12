<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    public function authenticate(LoginRequest $user) {
        if (Auth::attempt(['email'=> $user->email, 'password' => $user->password ])) {
            $user->session()->regenerate();
 
            return redirect()->intended('/');
        }
        return back()->withErrors([
            'email' => 'Email or password is incorrect!.',
        ])->onlyInput('email');
    }
}

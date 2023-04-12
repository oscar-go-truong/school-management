<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::prefix('/')->middleware('auth')->group(function() {
    Route::get('/', function () {
        return view('index');
    })->name('index');
});

Route::prefix('login')->group(function() {
    Route::get('/', function () {
        return view('auth.login');
    });
    Route::post('/', [AuthController::class, 'authenticate']
    )->name('login');
});

Route::get('/logout', [AuthController::class, 'logout']
)->name('logout');


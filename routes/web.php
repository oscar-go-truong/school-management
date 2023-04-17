<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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


Route::get('/login', [AuthController::class, 'login']);

Route::post('/login', [AuthController::class, 'authenticate']
)->name('login');

Route::get('/logout', [AuthController::class, 'logout']
)->name('logout');


Route::prefix('/')->middleware('auth')->group(function() {
    Route::get('/',[UserController::class, 'profile'] )->name('profile');
    Route::patch('users/status/{id}',[UserController::class, 'changeStatus'] )->name('admin.change.user.status');
    Route::get('users/table',[UserController::class, 'getTable'] )->name('admin.get.user.table');

    Route::resources(['users' => UserController::class]);
});



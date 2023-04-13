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



Route::prefix('/')->middleware('auth')->group(function() {
    Route::get('/',[UserController::class, 'profile'] )->name('profile');
    Route::prefix('/users')->group(function() {
        Route::get('/',[UserController::class, 'table'] )->name('table');

        Route::put('/status/{id}',[UserController::class, 'changeStatus'] )->name('admin.change.user.status');

        Route::delete('/{id}',[UserController::class, 'delete'] )->name('admin.delete.user.status');
    });
});



Route::get('/login', [AuthController::class, 'login']);

Route::post('/login', [AuthController::class, 'authenticate']
)->name('login');

Route::get('/logout', [AuthController::class, 'logout']
)->name('logout');


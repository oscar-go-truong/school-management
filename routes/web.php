<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
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

Route::post('/login', [AuthController::class, 'authenticate'])->name('login');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::prefix('/')->middleware('auth')->group(function () {
    Route::get('/', [UserController::class, 'profile'])->name('profile');

    Route::patch('users/status/{id}', [UserController::class, 'changeStatus'])->name('admin.change.user.status');
    Route::get('users/table', [UserController::class, 'getTable'])->name('admin.get.user.table');
    Route::resources(['users' => UserController::class]);

    Route::prefix('/courses/')->group(function () {
        Route::patch('status/{id}', [CourseController::class, 'changeStatus'])->name('admin.change.course.status');
        Route::get('table', [CourseController::class, 'getTable'])->name('user.get.courses.table');
        Route::get('{id}/teachers', [TeacherController::class, 'index'])->name('user.get.course.teachers');
        Route::get('{id}/teachers/table', [TeacherController::class, 'getTable'])->name('user.get.course.teachers.table');
        Route::get('{id}/students', [StudentController::class, 'index'])->name('user.get.course.students');
        Route::get('{id}/students/table', [StudentController::class, 'getTable'])->name('user.get.course.students.table');
    });
    Route::resources(['courses' => CourseController::class]);

    Route::prefix('/subjects/')->group(function () {
        Route::get('table', [SubjectController::class, 'getTable'])->name('user.get.subject.table');
        Route::get('{id}/courses', [SubjectController::class, 'getCourses'])->name('user.get.subject.courses');
        Route::get('{id}/courses/table', [SubjectController::class, 'getCoursesTable'])->name('user.get.subject.courses.table');
        Route::patch('status/{id}', [SubjectController::class, 'changeStatus'])->name('admin.change.subject.status');
    });
    Route::resources(['subjects' => SubjectController::class]);

    Route::prefix('/exams/')->group(function () {
        Route::get('table', [ExamController::class, 'getTable'])->name('user.get.exam.table');
      });
    Route::resource('exams', ExamController::class);
});

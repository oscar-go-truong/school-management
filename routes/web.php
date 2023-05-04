<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ScoreController;
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
    Route::patch('student/changeCourse', [StudentController::class, 'changeCourse'])->name('admin.change.user.course');

    Route::prefix('/users/')->middleware('auth.admin')->group(function () {
        Route::patch('status/{id}', [UserController::class, 'changeStatus'])->name('admin.change.user.status');
        Route::get('table', [UserController::class, 'getTable'])->name('admin.get.user.table');
        Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('create', [UserController::class, 'create'])->name('admin.get.users.create');
        Route::post('/', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('{user}', [UserController::class, 'show'])->name('admin.get.users.show');
        Route::get('{user}/edit', [UserController::class, 'edit'])->name('admin.get.users.edit');
        Route::put('{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });
    
    Route::prefix('/courses/')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('user.get.courses.index');
        Route::get('create', [CourseController::class, 'create'])->name('admin.get.courses.create')->middleware('auth.admin');
        Route::post('/courses', [CourseController::class, 'store'])->name('admin.courses.store')->middleware('auth.admin');
        Route::get('table', [CourseController::class, 'getTable'])->name('user.get.courses.table');
        Route::get('{course}', [CourseController::class, 'show'])->name('user.get.courses.show');
        Route::get('{course}/edit', [CourseController::class, 'edit'])->name('admin.get.courses.edit')->middleware('auth.admin');
        Route::patch('{course}', [CourseController::class, 'update'])->name('admin.courses.update')->middleware('auth.admin');
        Route::patch('status/{id}', [CourseController::class, 'changeStatus'])->name('admin.change.course.status')->middleware('auth.admin');
        Route::get('{id}/teachers', [TeacherController::class, 'index'])->name('user.get.course.teachers');
        Route::post('{id}/teachers', [TeacherController::class, 'store'])->name('admin.store.course.teachers')->middleware('auth.admin');
        Route::get('{id}/teachers/table', [TeacherController::class, 'getTable'])->name('user.get.course.teachers.table');
        Route::get('{id}/students', [StudentController::class, 'index'])->name('user.get.course.students');
        Route::post('{id}/students', [StudentController::class, 'store'])->name('admin.store.course.students')->middleware('auth.admin');
        Route::get('{id}/students/table', [StudentController::class, 'getTable'])->name('user.get.course.students.table');
        Route::get('{id}/exams', [ExamController::class, 'index'])->name('user.get.course.exams');
        Route::get('{id}/exams/table', [ExamController::class, 'getTable'])->name('user.get.course.exams.table');
    });
  
    Route::prefix('/subjects/')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('user.get.subjects.index');
        Route::get('create', [SubjectController::class, 'create'])->name('admin.get.subjects.create')->middleware('auth.admin');
        Route::post('/', [SubjectController::class, 'store'])->name('admin.subjects.store')->middleware('auth.admin');
        Route::get('table', [SubjectController::class, 'getTable'])->name('user.get.subject.table');
        Route::get('{subject}', [SubjectController::class, 'show'])->name('user.get.subjects.show');
        Route::get('{subject}/edit', [SubjectController::class, 'edit'])->name('admin.get.subjects.edit')->middleware('auth.admin');
        Route::patch('{subject}', [SubjectController::class, 'update'])->name('admin.subjects.update')->middleware('auth.admin');
        Route::get('{id}/courses', [CourseController::class, 'index'])->name('user.get.subject.courses');
        Route::get('{id}/courses/table', [CourseController::class, 'getTable'])->name('user.get.subject.courses.table');
        Route::patch('status/{id}', [SubjectController::class, 'changeStatus'])->name('admin.change.subject.status')->middleware('auth.admin');
    });

    Route::prefix('/exams/')->group(function () {
        Route::get('table', [ExamController::class, 'getTable'])->name('user.get.exam.table');
        Route::get('{id}/scores', [ScoreController::class, 'index'])->name('user.get.score');
        Route::get('{id}/scores/table', [ScoreController::class, 'getTable'])->name('user.get.score.table');
      });
    Route::resource('exams', ExamController::class);

    Route::prefix('/requests/')->group(function () {
        Route::get('table', [RequestController::class, 'getTable'])->name('user.get.request.table');
      });
    Route::resource('requests', RequestController::class);

});

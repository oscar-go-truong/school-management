<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCourseController;
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
    Route::patch('student/change-course', [StudentController::class, 'changeCourse'])->name('admin.change.user_course');

    Route::prefix('/users/')->middleware('auth.admin')->group(function () {
        Route::patch('status/{id}', [UserController::class, 'changeStatus'])->name('admin.change.user.status');
        Route::get('table', [UserController::class, 'getTable'])->name('admin.get.user.table');
    });
    Route::resource('users', UserController::class)->middleware('auth.admin');
    
    Route::prefix('/courses/')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('user.get.courses.index');
        Route::post('/', [CourseController::class, 'store'])->name('admin.courses.store')->middleware('auth.admin');
        Route::get('create', [CourseController::class, 'create'])->name('admin.get.courses.create')->middleware('auth.admin');
        Route::get('table', [CourseController::class, 'getTable'])->name('user.get.courses.table');
        Route::get('{id}', [CourseController::class, 'show'])->name('user.get.courses.show')->middleware('has.course');
        Route::get('{id}/edit', [CourseController::class, 'edit'])->name('admin.get.courses.edit')->middleware('auth.admin')->middleware('has.course');
        Route::patch('{id}', [CourseController::class, 'update'])->name('admin.courses.update')->middleware('auth.admin');
        Route::patch('status/{id}', [CourseController::class, 'changeStatus'])->name('admin.change.course.status')->middleware('auth.admin');
        Route::get('{id}/teachers', [TeacherController::class, 'index'])->name('user.get.course.teachers')->middleware('has.course');
        Route::post('{id}/teachers', [TeacherController::class, 'store'])->name('admin.store.course.teachers')->middleware('auth.admin');
        Route::get('{id}/teachers/table', [TeacherController::class, 'getTable'])->name('user.get.course.teachers.table')->middleware('has.course');
        Route::get('{id}/students', [StudentController::class, 'index'])->name('user.get.course.students')->middleware('has.course');
        Route::post('{id}/students', [StudentController::class, 'store'])->name('admin.store.course.students')->middleware('auth.admin');
        Route::get('{id}/students/table', [StudentController::class, 'getTable'])->name('user.get.course.students.table')->middleware('has.course');
        Route::get('{id}/students-list/export', [CourseController::class, 'exportStudentList'])->name('export.student.list');
    });
  
    Route::prefix('/subjects/')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('user.get.subjects.index');
        Route::get('create', [SubjectController::class, 'create'])->name('admin.get.subjects.create')->middleware('auth.admin');
        Route::post('/', [SubjectController::class, 'store'])->name('admin.subjects.store')->middleware('auth.admin');
        Route::get('table', [SubjectController::class, 'getTable'])->name('user.get.subject.table');
        Route::get('{id}', [SubjectController::class, 'show'])->name('user.get.subjects.show')->middleware('has.subject');
        Route::get('{id}/edit', [SubjectController::class, 'edit'])->name('admin.get.subjects.edit')->middleware('auth.admin')->middleware('has.subject');
        Route::patch('{id}', [SubjectController::class, 'update'])->name('admin.subjects.update')->middleware('auth.admin');
        Route::patch('status/{id}', [SubjectController::class, 'changeStatus'])->name('admin.change.subject.status')->middleware('auth.admin');
    });

    Route::prefix('/exams/')->group(function () {
        Route::get('table', [ExamController::class, 'getTable'])->name('user.get.exam.table');
        Route::get('{id}/scores', [ScoreController::class, 'index'])->name('user.get.score')->middleware('has.exam');
        Route::get('{id}/scores/table', [ScoreController::class, 'getTable'])->name('user.get.score.table')->middleware('has.exam');
        Route::post('{id}/scores/import-file', [ExamController::class, 'importScores'])->name('user.import.data.score')->middleware('auth.teacher')->middleware('has.exam');
        Route::get('{id}/scores/detach-file', [ExamController::class, 'detachFile'])->name('user.detach.data.score')->middleware('auth.teacher')->middleware('has.exam');
        Route::get('missing-user', [ExamController::class, 'getMissingUser'])->name('user.get.missing_user');
      });
    Route::resource('exams', ExamController::class);

    Route::prefix('/requests/')->group(function () {
        Route::get('table', [RequestController::class, 'getTable'])->name('user.get.request.table');
        Route::get('{id}', [RequestController::class, 'show'])->name('user.get.request.detail')->middleware('has.request');
        Route::patch('status/{id}',[RequestController::class, 'changeStatus'])->name('admin.change.request.status')->middleware('auth.admin');
        Route::patch('{id}/reject', [RequestController::class, 'reject'])->name('admin.reject.request')->middleware('auth.admin');
        Route::patch('{id}/approve', [RequestController::class, 'approve'])->name('admin.approve.request')->middleware('auth.admin');
        Route::post('review-score', [RequestController::class, 'storeReviewScoreRequest'])->name('student.create.review_score_request');
        Route::post('switch-course', [RequestController::class, 'storeSwitchCourseRequest'])->name('student.create.switch_course_request');
        Route::post('booking-room', [RequestController::class, 'storeBookingRoomRequest'])->name('hoomroom_teacher.create.booking_room.request');
        Route::post('edit-exam-scores', [RequestController::class, 'storeEditExamScoresRequest'])->name('hoomroom_teacher.create.edit_exam_scores.request');
      });
    Route::resource('requests', RequestController::class);

    Route::prefix('/schedules/')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('user.get.schedule.index');
        Route::get('/table', [ScheduleController::class, 'getTable'])->name('user.get.schedule.table');
        Route::get('events/create', [EventController::class, 'create'])->name('user.get.event.create')->middleware('auth.teacher');
        Route::post('events', [EventController::class, 'store'])->name('user.get.event.store')->middleware('auth.teacher');
        Route::get('/check-is-conflict-time', [ScheduleController::class, 'checkIsConflictTime'])->name('user.check.conflict_time')->middleware('auth.teacher');
      });

    Route::prefix('/rooms/')->group(function () {
        Route::get('/available', [RoomController::class, 'getAvailable'])->name('user.get.rooms.available');
      });
    Route::prefix('/user-courses/')->group(function () {
        Route::patch('status/{id}', [UserCourseController::class, 'changeStatus'])->name('admin.update.userCourse.status')->middleware('auth.admin');
        Route::delete('{id}', [UserCourseController::class, 'delete'])->name('admin.delete.userCourse.status')->middleware('auth.admin');
      });
});

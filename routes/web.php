<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ClassmateController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassworkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubjectmatterController;
use App\Http\Controllers\TeacherController;
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
Route::prefix('auth')->group(function () {
    Route::middleware('haslogin')->group(function () {
        Route::get('login', [AuthController::class, 'login']);
        Route::post('login', [AuthController::class, 'loginPost']);
    });
    Route::get('logout', function () {
        session()->flush();
        return redirect()->to('auth/login');
    });
});

Route::middleware('token')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);

    Route::middleware('superadmin')->group(function () {
        Route::resource('student', StudentController::class);
        Route::resource('teacher', TeacherController::class);
        Route::resource('class', ClassController::class);
        Route::resource('major', MajorController::class);
    });
    
    Route::middleware('student')->group(function () {
        Route::get('classmate', [ClassmateController::class, 'index']);
    });
    
    Route::resource('subject', SubjectController::class);
    Route::resource('subject.subjectmatter', SubjectmatterController::class);
    Route::resource('class.classroom', ClassroomController::class);
    Route::resource('class.classroom.subjectmatter', SubjectmatterController::class);
    Route::resource('class.classroom.subjectmatter.classwork', ClassworkController::class);
    Route::resource('classroom', ClassroomController::class);
    Route::resource('schedule', ScheduleController::class);

    Route::get('getchat/{classroom}/{time?}', [ClassroomController::class, 'getChat']);
    Route::post('postchat', [ClassroomController::class, 'postChat']);
});

<?php

use App\Http\Controllers\ClassgroupController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPageController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherPageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index']);

Route::get('/teachers/create', [TeacherController::class, 'create']);
Route::get('/teachers', [TeacherController::class, 'index']);
Route::post('/teachers', [TeacherController::class, 'store']);
Route::get('/teachers/{teacher}', [TeacherController::class, 'show']);
Route::get('/teachers/edit/{teacher}', [TeacherController::class, 'edit']);
Route::post('/teachers/{teacher}', [TeacherController::class, 'update']);
Route::get('/teachers/delete/{teacher}', [TeacherController::class, 'destroy']);

Route::get('/teacherpage', [TeacherPageController::class, 'index']);
Route::get('/teacherpage/edit/{teacher}', [TeacherPageController::class, 'edit']);
Route::post('/teacherpage/{teacher}', [TeacherPageController::class, 'update']);

Route::get('/students/create', [StudentController::class, 'create']);
Route::get('/students', [StudentController::class, 'index']);
Route::post('/students', [StudentController::class, 'store']);
Route::get('/students/{student}', [StudentController::class, 'show']);
Route::get('/students/edit/{student}', [StudentController::class, 'edit']);
Route::post('/students/{student}', [StudentController::class, 'update']);
Route::get('/students/delete/{student}', [StudentController::class, 'destroy']);

Route::get('/studentpage', [StudentPageController::class, 'index']);
Route::get('/studentpage/edit/{student}', [StudentPageController::class, 'edit']);
Route::post('/studentpage/{student}', [StudentPageController::class, 'update']);

Route::get('/classgroups/create', [ClassgroupController::class, 'create']);
Route::get('/classgroups', [ClassgroupController::class, 'index']);
Route::post('/classgroups', [ClassgroupController::class, 'store']);
Route::get('/classgroups/{classgroup}', [ClassgroupController::class, 'show']);
Route::get('/classgroups/edit/{classgroup}', [ClassgroupController::class, 'edit']);
Route::post('/classgroups/{classgroup}', [ClassgroupController::class, 'update']);
Route::get('/classgroups/delete/{classgroup}', [ClassgroupController::class, 'destroy']);

Route::get('/classrooms/create', [ClassroomController::class, 'create']);
Route::get('/classrooms', [ClassroomController::class, 'index']);
Route::post('/classrooms', [ClassroomController::class, 'store']);
Route::get('/classrooms/{classroom}', [ClassroomController::class, 'show']);
Route::get('/classrooms/edit/{classroom}', [ClassroomController::class, 'edit']);
Route::post('/classrooms/{classroom}', [ClassroomController::class, 'update']);
Route::get('/classrooms/delete/{classroom}', [ClassroomController::class, 'destroy']);

Route::get('/levels/create', [LevelController::class, 'create']);
Route::get('/levels', [LevelController::class, 'index']);
Route::post('/levels', [LevelController::class, 'store']);
Route::get('/levels/{level}', [LevelController::class, 'show']);
Route::get('/levels/edit/{level}', [LevelController::class, 'edit']);
Route::post('/levels/{level}', [LevelController::class, 'update']);
Route::get('/levels/delete/{level}', [LevelController::class, 'destroy']);

Route::get('/subjects/create', [SubjectController::class, 'create']);
Route::get('/subjects', [SubjectController::class, 'index']);
Route::post('/subjects', [SubjectController::class, 'store']);
Route::get('/subjects/{subject}', [SubjectController::class, 'show']);
Route::get('/subjects/edit/{subject}', [SubjectController::class, 'edit']);
Route::post('/subjects/{subject}', [SubjectController::class, 'update']);
Route::get('/subjects/delete/{subject}', [SubjectController::class, 'destroy']);

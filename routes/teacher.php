<?php

use App\Http\Controllers\Teacher\CourseClassroomController;
use App\Http\Controllers\Teacher\CourseTeacherController;
use App\Http\Controllers\Teacher\DashboardTeacherController;
use App\Http\Controllers\Teacher\ScheduleTeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('teachers')->middleware(['auth','role:Teacher'])->group(function () {
    Route::get('dashboard', DashboardTeacherController::class)->name('teachers.dashboard');

    Route::controller(CourseTeacherController::class)->group(function(){
        Route::get('courses', 'index')->name('teachers.courses.index');
        Route::get('courses/{course}/detail', 'show')->name('teachers.courses.show');
    });

    Route::controller(CourseClassroomController::class)->group(function(){
        Route::get('courses/{course}/classrooms/{classroom}', 'index')->name('teachers.classrooms.index');
        Route::put('courses/{course}/classrooms/{classroom}/synchronize', 'sync')->name('teachers.classrooms.sync');
    });

    Route::get('schedules', ScheduleTeacherController::class)->name('teachers.schedules.index');
});

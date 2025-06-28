<?php

use App\Http\Controllers\Teacher\CourseTeacherController;
use App\Http\Controllers\Teacher\DashboardTeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('teachers')->middleware(['auth','role:Teacher'])->group(function () {
    Route::get('dashboard', DashboardTeacherController::class)->name('teachers.dashboard');

    Route::controller(CourseTeacherController::class)->group(function(){
        Route::get('courses', 'index')->name('teachers.courses.index');
        Route::get('courses/{course}/detail', 'show')->name('teachers.courses.show');
    });
});

<?php

use App\Http\Controllers\Teacher\DashboardTeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('teachers')->middleware(['auth','role:Teacher'])->group(function () {
    Route::get('dashboard', DashboardTeacherController::class)->name('teachers.dashboard');
});

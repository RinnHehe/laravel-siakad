<?php

use App\Http\Controllers\Teacher\DashboardTeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('teachers')->group(function () {
    Route::get('dashboard', DashboardTeacherController::class)->name('teachers.dashboard');
});

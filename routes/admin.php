<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth','role:Admin'])->group(function () {
    Route::get('dashboard', DashboardAdminController::class,)->name('admin.dashboard');
});

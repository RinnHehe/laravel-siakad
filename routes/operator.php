<?php

use App\Http\Controllers\Operator\DashboardOperatorController;
use Illuminate\Support\Facades\Route;

Route::prefix('operators')->middleware(['auth','role:Operator'])->group(function () {
    Route::get('dashboard', DashboardOperatorController::class)->name('operators.dashboard');
}); 
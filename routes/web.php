<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardAdminController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/','login');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/operator.php';
require __DIR__.'/teacher.php';
require __DIR__.'/student.php';
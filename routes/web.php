<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return to_route('dashboard');
    } else {
        return to_route('login');
    }
});

Route::get('dashboard', function () {
    if (Auth::user()->hasRole('Admin')) {
        return Inertia::location(route('admin.dashboard', absolute: false));
    } else if (Auth::user()->hasRole('Teacher')) {
        return Inertia::location(route('teachers.dashboard', absolute: false));
    } else if (Auth::user()->hasRole('Operator')) {
        return Inertia::location(route('operators.dashboard', absolute: false));
    } else if (Auth::user()->hasRole('Student')) {
        return Inertia::location(route('students.dashboard', absolute: false));
    } else {
        abort(404, 'Not Found');
    }

})->middleware(['auth', 'verified'])->name('dashboard');

Route::controller(PaymentController::class)->group(function () {
    Route::post('payments', 'create')->name('payments.create');
    Route::post('payments/callback', 'callback')->name('payments.callback');
    Route::get('payments/success', 'success')->name('payments.success');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/operator.php';
require __DIR__.'/teacher.php';
require __DIR__.'/student.php';
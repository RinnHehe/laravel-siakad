<?php

use App\Http\Controllers\Operator\DashboardOperatorController;
use App\Http\Controllers\Operator\TeacherOperatorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('operators')->middleware('role:Operator')->group(function () {
        Route::get('dashboard', DashboardOperatorController::class)->name('operators.dashboard');

        Route::controller(TeacherOperatorController::class)->group(function(){
            Route::get('teachers', 'index')->name('operators.teachers.index');
            Route::get('teachers/create', 'create')->name('operators.teachers.create');
            Route::post('teachers/create', 'store')->name('operators.teachers.store');
            Route::get('teachers/edit/{teacher:teacher_number}', 'edit')->name('operators.teachers.edit');
            Route::put('teachers/edit/{teacher:teacher_number}', 'update')->name('operators.teachers.update');
            Route::delete('teachers/destroy/{teacher:teacher_number}', 'destroy')->name('operators.teachers.destroy');
        });
    });
});

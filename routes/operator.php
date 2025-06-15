<?php

use App\Http\Controllers\Operator\DashboardOperatorController;
use App\Http\Controllers\Operator\StudentOperatorController;
use App\Http\Controllers\Operator\TeacherOperatorController;
use Illuminate\Support\Facades\Route;

Route::prefix('operators')->middleware(['auth','role:Operator'])->group(function () {
    Route::get('dashboard', DashboardOperatorController::class)->name('operators.dashboard');

    Route::controller(StudentOperatorController::class)->group(function(){
        Route::get('students', 'index')->name('operators.students.index');
        Route::get('students/create', 'create')->name('operators.students.create');
        Route::post('students/create', 'store')->name('operators.students.store');
        Route::get('students/edit/{student:student_number}', 'edit')->name('operators.students.edit');
        Route::put('students/edit/{student:student_number}', 'update')->name('operators.students.update');
        Route::delete('students/destroy/{student:student_number}', 'destroy')->name('operators.students.destroy');
    });

    Route::controller(TeacherOperatorController::class)->group(function(){
        Route::get('teachers', 'index')->name('operators.teachers.index');
        Route::get('teachers/create', 'create')->name('operators.teachers.create');
        Route::post('teachers/create', 'store')->name('operators.teachers.store');
        Route::get('teachers/edit/{teacher:teacher_number}', 'edit')->name('operators.teachers.edit');
        Route::post('teachers/edit/{teacher:teacher_number}', 'update')->name('operators.teachers.update');
        Route::post('teachers/destroy/{teacher:teacher_number}', 'destroy')->name('operators.teachers.destroy');
    });

}); 
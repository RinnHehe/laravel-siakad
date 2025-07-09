<?php

use App\Http\Controllers\Student\DashboardStudentController;
use App\Http\Controllers\Student\FeeStudentController;
use App\Http\Controllers\Student\ScheduleStudentController;
use App\Http\Controllers\Student\StudyPlanStudentController;
use App\Http\Controllers\Student\StudyResultStudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('students')->middleware(['auth','role:Student'])->group(function () {
    Route::get('dashboard', DashboardStudentController::class)->name('students.dashboard');

    Route::controller(StudyPlanStudentController::class)->group(function(){
        Route::get('study-plans', 'index')->name('students.study.plans.index');
        Route::get('study-plans/create', 'create')->name('students.study.plans.create');
        Route::post('study-plans/create', 'store')->name('students.study.plans.store');
        Route::get('study-plans/detail/{studyPlan}', 'show')->name('students.study.plans.show');
    });

    Route::get('schedules', ScheduleStudentController::class)->name('students.schedules.index');
    Route::get('fees', FeeStudentController::class)->name('students.fees.index');
    Route::get('study-results', StudyResultStudentController::class)->name('students.study.results.index');
    Route::get('/study-results/{id}/download', [StudyResultStudentController::class, 'download'])
    ->name('student.study-results.download');

});

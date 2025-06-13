<?php

use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\ClassroomStudentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\FeeGroupController;
use App\Http\Controllers\Admin\OperatorController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth','role:Admin'])->group(function () {
    Route::get('dashboard', DashboardAdminController::class,)->name('admin.dashboard');

    Route::controller(FacultyController::class)->group(function () {
        Route::get('faculties', 'index')->name('admin.faculties.index');
        Route::get('faculties/create', 'create')->name('admin.faculties.create');
        Route::post('faculties/create', 'store')->name('admin.faculties.store');
        Route::get('faculties/edit/{faculty:slug}', 'edit')->name('admin.faculties.edit');
        Route::put('faculties/edit/{faculty:slug}', 'update')->name('admin.faculties.update');
        Route::delete('faculties/delete/{faculty:slug}', 'destroy')->name('admin.faculties.destroy');
    });

    Route::controller(DepartmentController::class)->group(function () {
        Route::get('departments', 'index')->name('admin.departments.index');
        Route::get('departments/create', 'create')->name('admin.departments.create');
        Route::post('departments/create', 'store')->name('admin.departments.store');
        Route::get('departments/edit/{department:slug}', 'edit')->name('admin.departments.edit');
        Route::put('departments/edit/{department:slug}', 'update')->name('admin.departments.update');
        Route::delete('departments/delete/{department:slug}', 'destroy')->name('admin.departments.destroy');
    });

    Route::controller(AcademicYearController::class)->group(function () {
        Route::get('academic-years', 'index')->name('admin.academic-years.index');
        Route::get('academic-years/create', 'create')->name('admin.academic-years.create');
        Route::post('academic-years/create', 'store')->name('admin.academic-years.store');
        Route::get('academic-years/edit/{academicYear:slug}', 'edit')->name('admin.academic-years.edit');
        Route::put('academic-years/edit/{academicYear:slug}', 'update')->name('admin.academic-years.update');
        Route::delete('academic-years/delete/{academicYear:slug}', 'destroy')->name('admin.academic-years.destroy');
    });

    Route::controller(ClassroomController::class)->group(function () {
        Route::get('classrooms', 'index')->name('admin.classrooms.index');
        Route::get('classrooms/create', 'create')->name('admin.classrooms.create');
        Route::post('classrooms/create', 'store')->name('admin.classrooms.store');
        Route::get('classrooms/edit/{classroom:slug}', 'edit')->name('admin.classrooms.edit');
        Route::put('classrooms/edit/{classroom:slug}', 'update')->name('admin.classrooms.update');
        Route::delete('classrooms/delete/{classroom:slug}', 'destroy')->name('admin.classrooms.destroy');
    });

    Route::controller(RoleController::class)->group(function () {
        Route::get('roles', 'index')->name('admin.roles.index');
        Route::get('roles/create', 'create')->name('admin.roles.create');
        Route::post('roles/create', 'store')->name('admin.roles.store');
        Route::get('roles/edit/{role}', 'edit')->name('admin.roles.edit');
        Route::put('roles/edit/{role}', 'update')->name('admin.roles.update');
        Route::delete('roles/delete/{role}', 'destroy')->name('admin.roles.destroy');
    });

    Route::controller(FeeGroupController::class)->group(function () {
        Route::get('fee-groups', 'index')->name('admin.fee-groups.index');
        Route::get('fee-groups/create', 'create')->name('admin.fee-groups.create');
        Route::post('fee-groups/create', 'store')->name('admin.fee-groups.store');
        Route::get('fee-groups/edit/{feeGroup}', 'edit')->name('admin.fee-groups.edit');
        Route::put('fee-groups/edit/{feeGroup}', 'update')->name('admin.fee-groups.update');
        Route::delete('fee-groups/delete/{feeGroup}', 'destroy')->name('admin.fee-groups.destroy');
    });


    Route::controller(StudentController::class)->group(function () {
        Route::get('students', 'index')->name('admin.students.index');
        Route::get('students/create', 'create')->name('admin.students.create');
        Route::post('students/create', 'store')->name('admin.students.store');
        Route::get('students/edit/{student:student_number}', 'edit')->name('admin.students.edit');
        Route::put('students/edit/{student:student_number}', 'update')->name('admin.students.update');
        Route::delete('students/delete/{student:student_number}', 'destroy')->name('admin.students.destroy');
    });

    Route::controller(ClassroomStudentController::class)->group(function () {
        Route::get('classroom/students/{classroom:slug}', 'index')->name('admin.classroom-students.index');
        Route::put('classroom/students/{classroom:slug}/sync', 'sync')->name('admin.classroom-students.sync');
        Route::delete('classroom/students/{classroom:slug}/destroy/{student:student_number}', 'destroy')->name('admin.classroom-students.destroy');
     });

    Route::controller(TeacherController::class)->group(function () {
        Route::get('teachers', 'index')->name('admin.teachers.index');
        Route::get('teachers/create', 'create')->name('admin.teachers.create');
        Route::post('teachers/create', 'store')->name('admin.teachers.store');
        Route::get('teachers/edit/{teacher:teacher_number}', 'edit')->name('admin.teachers.edit');
        Route::put('teachers/edit/{teacher:teacher_number}', 'update')->name('admin.teachers.update');
        Route::delete('teachers/delete/{teacher:teacher_number}', 'destroy')->name('admin.teachers.destroy');
    });

    Route::controller(CourseController::class)->group(function () {
        Route::get('courses', 'index')->name('admin.courses.index');
        Route::get('courses/create', 'create')->name('admin.courses.create');
        Route::post('courses/create', 'store')->name('admin.courses.store');
        Route::get('courses/edit/{course:code}', 'edit')->name('admin.courses.edit');
        Route::put('courses/edit/{course:code}', 'update')->name('admin.courses.update');
        Route::delete('courses/delete/{course:code}', 'destroy')->name('admin.courses.destroy');
    });

    Route::controller(OperatorController::class)->group(function () {
        Route::get('operators', 'index')->name('admin.operators.index');
        Route::get('operators/create', 'create')->name('admin.operators.create');
        Route::post('operators/create', 'store')->name('admin.operators.store');
        Route::get('operators/edit/{operator:employee_number}', 'edit')->name('admin.operators.edit');
        Route::put('operators/edit/{operator:employee_number}', 'update')->name('admin.operators.update');
        Route::delete('operators/delete/{operator:employee_number}', 'destroy')->name('admin.operators.destroy');
    });

    Route::controller(ScheduleController::class)->group(function () {
        Route::get('schedules', 'index')->name('admin.schedules.index');
        Route::get('schedules/create', 'create')->name('admin.schedules.create');
        Route::post('schedules/create', 'store')->name('admin.schedules.store');
        Route::get('schedules/edit/{schedule}', 'edit')->name('admin.schedules.edit');
        Route::put('schedules/edit/{schedule}', 'update')->name('admin.schedules.update');
        Route::delete('schedules/delete/{schedule}', 'destroy')->name('admin.schedules.destroy');
    });
});

<?php

use App\Http\Middleware\CheckActiveAcademicYear;
use App\Http\Middleware\CheckFeeStudent;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Http\Middleware\ValidateClassroom;
use App\Http\Middleware\ValidateCourseSchedule;
use App\Http\Middleware\ValidateCourse;
use App\Http\Middleware\ValidateDepartment;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ])->validateCsrfTokens(except: [
            'payments/callback',
        ])->alias(aliases:[
            'role' => RoleMiddleware::class,
            'checkActiveAcademicYear' => CheckActiveAcademicYear::class,
            'checkFeeStudent' => CheckFeeStudent::class,
            'validateClassroom' => ValidateClassroom::class,
            'validateCourseSchedule' => ValidateCourseSchedule::class,
            'validateCourse' => ValidateCourse::class,
            'validateDepartment' => ValidateDepartment::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

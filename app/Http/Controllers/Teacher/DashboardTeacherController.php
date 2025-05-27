<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Classroom;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardTeacherController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Inertia\Response
     */
    public function __invoke(): Response
    {
        return inertia('Teachers/Dashboard', [
            'page_settings' => [
                'title' => 'Dashboard',
                'subtitle' => 'Menampilkan semua statistik pada platform ini.',
            ],
            'count' => [
                'courses' => Course::query()
                    ->where('teacher_id', Auth::user()->teacher->id)
                    ->count(),
                'classrooms' => Classroom::query()
                    ->whereHas('schedules.course', fn($query) => $query->where('teacher_id', Auth::user()->teacher->id))
                    ->count(),
                'schedules' => Schedule::query()
                    ->whereHas('course', fn($query) => $query->where('teacher_id', Auth::user()->teacher->id))
                    ->count()
            ]
        ]);
    }
}

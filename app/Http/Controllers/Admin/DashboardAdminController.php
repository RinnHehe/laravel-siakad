<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Classroom;
use App\Models\Course;
use Inertia\Inertia;
use Inertia\Response;

class DashboardAdminController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Inertia\Response
     */
    public function __invoke(): Response
    {
        return inertia('Admin/Dashboard', [
            'page_settings' => [
                'title' => 'Dashboard',
                'subtitle' => 'Menampilkan semua statistik pada platform ini.',
            ],
            'count' => [
                'faculties' => Faculty::count(),
                'departments' => Department::count(),
                'classrooms' => Classroom::count(),
                'courses' => Course::count(),
            ],
        ]);
    }
}

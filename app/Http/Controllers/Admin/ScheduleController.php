<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ScheduleResource;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ScheduleController extends Controller
{
    public function index(): Response
    {
        $schedules = Schedule::query()
        ->select(['schedules.id', 'schedules.faculty_id', 'schedules.department_id', 'schedules.course_id', 'schedules.classroom_id', 'schedules.academic_year_id', 'schedules.start_time', 'schedules.end_time', 'schedules.day_of_week', 'schedules.quota', 'schedules.created_at'])
        ->filter(request()->only('search'))
        ->sorting(request()->only('field', 'direction'))
        ->with(['faculty', 'department', 'course', 'classroom', 'academicYear'])
        ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/Schedules/Index', [
            'page_settings' => [
                'title' => 'Jadwal',
                'subtitle' => 'Menampilkan semua jadwal yang tersedia pada Politeknik Negeri Kotabaru',
            ],
            'schedules' => ScheduleResource::collection($schedules)->additional([
                'meta' => [
                    'has_pages' => $schedules->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],
        ]);
    }
}

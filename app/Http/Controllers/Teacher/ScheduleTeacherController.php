<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\ScheduleDay;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ScheduleTeacherController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $courses = Course::query()
            ->where('teacher_id', Auth::user()->teacher->id)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->pluck('id');

        $schedules = Schedule::query()
            ->whereIn('course_id', $courses)
            ->with('course')
            ->get();

        $days = ScheduleDay::cases();
        $scheduleTable = [];

        foreach ($schedules as $schedule) {
            $startTime = substr($schedule->start_time, 0, 5);
            $endTime = substr($schedule->end_time, 0, 5);
            $day = $schedule->day_of_week->value;

            $scheduleTable[$startTime][$day] = [
                'course' => $schedule->course->name,
                'end_time' => $endTime,
            ];
        }

        $scheduleTable = collect($scheduleTable)->sortKeys();

        return Inertia::render('Teachers/Schedules/Index', [
            'page_settings' => [
                'title' => 'Jadwal',
                'subtitle' => 'Menampilkan semua jadwal mengajar anda',
            ],
            'scheduleTable' => $scheduleTable,
            'days' => $days,
        ]);
    }
}

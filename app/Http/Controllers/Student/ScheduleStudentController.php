<?php

namespace App\Http\Controllers\Student;

use App\Enums\ScheduleDay;
use App\Http\Controllers\Controller;
use App\Models\StudyPlan;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ScheduleStudentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response|RedirectResponse
    {
        $student = Auth::user()->student;
        $academicYear = activeAcademicYear();

        $studyPlan = StudyPlan::query()
            ->where('student_id', $student->id)
            ->where('academic_year_id', $academicYear->id)
            ->approved()
            ->first();

        if (!$studyPlan) {
            flashMessage('Anda belum mengajukan krs', 'warning');
            return to_route('students.study-plans.index');
        }

        // Ambil schedules melalui relasi many-to-many
        $schedules = $studyPlan->schedules()->with('course')->get();

        Log::info('Schedules count from relationship:', [$schedules->count()]);

        // Ambil semua hari dalam format yang benar
        $days = collect(ScheduleDay::cases())->map(function ($day) {
            return $day->value;
        })->toArray();

        $scheduleTable = [];

        foreach ($schedules as $schedule) {
            $startTime = substr($schedule->start_time, 0, 5);
            $endTime = substr($schedule->end_time, 0, 5);
            $day = $schedule->day_of_week->value;

            // Fix time format if needed
            if ($endTime === '02:00' && $startTime === '12:00') {
                $endTime = '14:00';
            }

            $scheduleTable[$startTime][$day] = [
                'course' => $schedule->course->name,
                'code' => $schedule->course->code,
                'end_time' => $endTime,
            ];
        }

        // Debug: tampilkan hasil akhir
        Log::info('Final Days:', $days);
        Log::info('Final Schedule Table:', $scheduleTable);

        // IMPORTANT: Debug JSON serialization
        Log::info('Schedule Table JSON:', [json_encode($scheduleTable)]);
        Log::info('Days JSON:', [json_encode($days)]);

        // Test serialization
        $testData = [
            'scheduleTable' => $scheduleTable,
            'days' => $days,
        ];
        Log::info('Test serialization:', [json_encode($testData)]);

        $inertiaData = [
            'page_settings' => [
                'title' => 'Jadwal Kuliah',
                'subtitle' => 'Menampilkan semua jadwal yang tersedia',
            ],
            'scheduleTable' => $scheduleTable,
            'days' => $days,
            // Add debugging data
            'debug' => [
                'scheduleTableCount' => count($scheduleTable),
                'daysCount' => count($days),
                'rawScheduleTable' => $scheduleTable,
            ],
        ];

        Log::info('Inertia data to be sent:', $inertiaData);

        return Inertia::render('Students/Schedules/Index', $inertiaData);
    }
}
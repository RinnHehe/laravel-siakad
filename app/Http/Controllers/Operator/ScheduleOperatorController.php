<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Enums\ScheduleDay;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\ScheduleOperatorRequest;
use App\Http\Resources\Operator\ScheduleOperatorResource;
use App\Models\Course;
use App\Models\Classroom;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ScheduleOperatorController extends Controller
{
    public function index(): Response
    {
        $operator = Auth::user()->operator;

        $schedules = Schedule::query()
            ->select(['schedules.id', 'schedules.faculty_id', 'schedules.department_id', 'schedules.course_id', 'schedules.classroom_id', 'schedules.start_time', 'schedules.end_time', 'schedules.day_of_week', 'schedules.quota', 'schedules.academic_year_id', 'schedules.created_at',])
            ->filter(request()->only('search'))
            ->sorting(request()->only('field', 'direction'))
            ->where('schedules.faculty_id', Auth::user()->operator->faculty_id)
            ->where('schedules.department_id', Auth::user()->operator->department_id)
            ->with(['course', 'classroom', 'academicYear'])
            ->paginate(request()->load ?? 10);

        return Inertia::render('Operators/Schedules/Index', [
            'page_settings' => [
                'title' => 'Jadwal',
                'subtitle' => "Daftar semua jadwal yang terdaftar di Program Studi {$operator->faculty?->name}",
            ],
            'schedules' => ScheduleOperatorResource::collection($schedules)->additional([
                'meta' => [
                    'has_pages' => $schedules->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
            ]
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Operators/Schedules/Create', [
            'page_settings' => [
                'title' => 'Tambah Jadwal',
                'subtitle' => 'Tambahkan data Jadwal baru',
                'method' => 'POST',
                'action' => route('operators.schedules.store'),
            ],
            'courses' => Course::query()
                ->select(['id', 'name',])
                ->where('faculty_id', Auth::user()->operator->faculty_id)
                ->where('department_id', Auth::user()->operator->department_id)
                ->get()
                ->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
            'classrooms' => Classroom::query()
                ->select(['id', 'name',])
                ->where('faculty_id', Auth::user()->operator->faculty_id)
                ->where('department_id', Auth::user()->operator->department_id)
                ->get()
                ->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
            'days' => ScheduleDay::options()
        ]);
    }

    public function store(ScheduleOperatorRequest $request): RedirectResponse
    {
        try {
            Schedule::create([
                'faculty_id' => Auth::user()->operator->faculty_id,
                'department_id' => Auth::user()->operator->department_id,
                'course_id' => $request->validated('course_id'),
                'classroom_id' => $request->validated('classroom_id'),
                'academic_year_id' => activeAcademicYear()->id,
                'start_time' => $request->validated('start_time'),
                'end_time' => $request->validated('end_time'),
                'day_of_week' => $request->validated('day_of_week'),
                'quota' => 9999,
            ]);

            flashMessage(MessageType::CREATED->message('Jadwal'));
            return to_route('operators.schedules.index');

        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.schedules.index');
        }
    }

    public function edit(Schedule $schedule): Response
    {
        return Inertia::render('Operators/Schedules/Edit', [
            'page_settings' => [
                'title' => 'Edit Jadwal',
                'subtitle' => 'Edit data Jadwal',
                'method' => 'PUT',
                'action' => route('operators.schedules.update', $schedule),
            ],
            'schedule' => $schedule,
            'courses' => Course::query()
                ->select(['id', 'name',])
                ->where('faculty_id', Auth::user()->operator->faculty_id)
                ->where('department_id', Auth::user()->operator->department_id)
                ->get()
                ->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
            'classrooms' => Classroom::query()
                ->select(['id', 'name',])
                ->where('faculty_id', Auth::user()->operator->faculty_id)
                ->where('department_id', Auth::user()->operator->department_id)
                ->get()
                ->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
            'days' => ScheduleDay::options()
        ]);
    }

    public function update(Schedule $schedule, ScheduleOperatorRequest $request): RedirectResponse
    {
        try {
            $schedule->update([
                'course_id' => $request->validated('course_id'),
                'classroom_id' => $request->validated('classroom_id'),
                'start_time' => $request->validated('start_time'),
                'end_time' => $request->validated('end_time'),
                'day_of_week' => $request->validated('day_of_week'),
                'quota' => 9999,
            ]);

            flashMessage(MessageType::UPDATED->message('Jadwal'));
            return to_route('operators.schedules.index');

        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.schedules.index');
        }
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        try {
            $schedule->delete();

            flashMessage(MessageType::DELETED->message('Jadwal'));
            return to_route('operators.schedules.index');

        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.schedules.index');
        }
    }
}

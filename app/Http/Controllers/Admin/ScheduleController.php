<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Enums\ScheduleDay;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScheduleRequest;
use App\Http\Resources\Admin\ScheduleResource;
use App\Models\Classroom;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Schedule;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

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

    public function create(): Response
    {
        return Inertia::render('Admin/Schedules/Create', [
            'page_settings' => [
                'title' => 'Jadwal',
                'subtitle' => 'Buat jadwal baru disini. Klik Simpan setelah selesai.',
                'method' => 'POST',
                'action' => route('admin.schedules.store'),
            ],
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map( fn($item) => [
                'label' => $item->name,
                'value' => $item->id,
            ]),
            'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map( fn($item) => [
                'label' => $item->name,
                'value' => $item->id,
            ]),
            'courses' => Course::query()->select(['id', 'name'])->orderBy('name')->get()->map( fn($item) => [
                'label' => $item->name,
                'value' => $item->id,
            ]),
            'classrooms' => Classroom::query()->select(['id', 'name'])->orderBy('name')->get()->map( fn($item) => [
                'label' => $item->name,
                'value' => $item->id,
            ]),
            'days' => ScheduleDay::options(),
        ]);
    }

    public function store(ScheduleRequest $request): RedirectResponse
    {
        try {
            Schedule::create([
                'faculty_id' => $request->validated('faculty_id'),
                'department_id' => $request->validated('department_id'),
                'course_id' => $request->validated('course_id'),
                'classroom_id' => $request->validated('classroom_id'),
                'academic_year_id' => activeAcademicYear()->id,
                'start_time' => $request->validated('start_time'),
                'end_time' => $request->validated('end_time'),
                'day_of_week' => $request->validated('day_of_week'),
                'quota' => $request->validated('quota'),
            ]);

            flashMessage(MessageType::CREATED->message('Jadwal'));
            return to_route('admin.schedules.index');

        } catch (Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.schedules.index');
        }
    }

    public function edit(Schedule $schedule): Response
    {
        return Inertia::render('Admin/Schedules/Edit', [
            'page_settings' => [
                'title' => 'Jadwal',
                'subtitle' => 'Edit jadwal disini. Klik Simpan setelah selesai.',
                'method' => 'PUT',
                'action' => route('admin.schedules.update', $schedule),
            ],
            'schedule' => $schedule,
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map( fn($item) => [
                'label' => $item->name,
                'value' => $item->id,
            ]),
            'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map( fn($item) => [
                'label' => $item->name,
                'value' => $item->id,
            ]),
            'courses' => Course::query()->select(['id', 'name'])->orderBy('name')->get()->map( fn($item) => [
                'label' => $item->name,
                'value' => $item->id,
            ]),
            'classrooms' => Classroom::query()->select(['id', 'name'])->orderBy('name')->get()->map( fn($item) => [
                'label' => $item->name,
                'value' => $item->id,
            ]),
            'days' => ScheduleDay::options(),
        ]);
    }

    public function update(ScheduleRequest $request, Schedule $schedule): RedirectResponse
    {
        try {
            $schedule->update([
                'faculty_id' => $request->validated('faculty_id'),
                'department_id' => $request->validated('department_id'),
                'course_id' => $request->validated('course_id'),
                'classroom_id' => $request->validated('classroom_id'),
                'start_time' => $request->validated('start_time'),
                'end_time' => $request->validated('end_time'),
                'day_of_week' => $request->validated('day_of_week'),
                'quota' => $request->validated('quota'),
            ]);

            flashMessage(MessageType::UPDATED->message('Jadwal'));
            return to_route('admin.schedules.index');

        } catch (Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.schedules.index');
        }
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        try {
            $schedule->delete();
            flashMessage(MessageType::DELETED->message('Jadwal'));
            return to_route('admin.schedules.index');
        } catch (Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.schedules.index');
        }
    }
}

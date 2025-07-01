<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Http\Resources\Admin\CourseResource;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Throwable;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CourseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('validateDepartment', ['store', 'update']),
        ];
    }
    public function index(): Response
    {
        $courses = Course::query()
            ->select(['courses.id', 'courses.faculty_id', 'courses.department_id', 'courses.teacher_id', 'courses.code',
            'courses.name', 'courses.credit', 'courses.semester', 'courses.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['faculty', 'department', 'teacher'])
            ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/Courses/Index', [
            'page_settings' => [
                'title' => 'Mata Kuliah',
                'subtitle' => 'Menampilkan semua mata kuliah yang ada di Politeknik Negeri Kotabaru',
            ],
            'courses' => CourseResource::collection($courses)->additional([
                'meta' => [
                    'has_pages' => $courses->hasPages(),
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
        return Inertia::render('Admin/Courses/Create', [
            'page_settings' => [
                'title' => 'Tambah Mata Kuliah',
                'subtitle' => 'Tambahkan mata kuliah baru disini. Klik simpan untuk menyimpan data.',
                'method' => 'POST',
                'action' => route('admin.courses.store'),
            ],
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'teachers' => Teacher::query()->select(['id', 'user_id'])->whereHas('user', function($query) {
                $query->whereHas('roles', fn($query) => $query->where('name', 'Teacher'))->orderBy('name');
            })->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->user?->name,
            ])
        ]);
    }

    public function store(CourseRequest $request): RedirectResponse
    {
        try {
            Course::create([
                'faculty_id' => $request->validated('faculty_id'),
                'department_id' => $request->validated('department_id'),
                'teacher_id' => $request->validated('teacher_id'),
                'academic_year' => activeAcademicYear()->id,
                'code' => str()->random(10),
                'name' => $request->validated('name'),
                'credit' => $request->validated('credit'),
                'semester' => $request->validated('semester'),
            ]);

            flashMessage(MessageType::CREATED->message('Mata kuliah'));
            return to_route('admin.courses.index');

        } catch (Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.courses.index');
        }
    }

    public function edit(Course $course): Response
    {
        return Inertia::render('Admin/Courses/Edit', [
            'page_settings' => [
                'title' => 'Edit Mata Kuliah',
                'subtitle' => 'Edit mata kuliah disini. Klik simpan untuk menyimpan data.',
                'method' => 'PUT',
                'action' => route('admin.courses.update', $course),
            ],
            'course' => $course,
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'teachers' => Teacher::query()->select(['id', 'user_id'])->whereHas('user', function($query) {
                $query->whereHas('roles', fn($query) => $query->where('name', 'Teacher'))->orderBy('name');
            })->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->user?->name,
            ])
        ]);
    }

    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        try {
            $course->update([
                'faculty_id' => $request->validated('faculty_id'),
                'department_id' => $request->validated('department_id'),
                'teacher_id' => $request->validated('teacher_id'),
                'name' => $request->validated('name'),
                'credit' => $request->validated('credit'),
                'semester' => $request->validated('semester'),
            ]);

            flashMessage(MessageType::UPDATED->message('Mata kuliah'));
            return to_route('admin.courses.index');

        } catch (Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.courses.index');
        }
    }

    public function destroy(Course $course): RedirectResponse
    {
        try {
            $course->delete();
            flashMessage(MessageType::DELETED->message('Mata kuliah'));
            return to_route('admin.courses.index');
        } catch (Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.courses.index');
        }
    }
}

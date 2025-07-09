<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\CourseOperatorRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use App\Http\Resources\Operator\CourseOperatorResource;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Throwable;

class CourseOperatorController extends Controller
{
    public function index(): Response
    {
        $operator = Auth::user()->operator;

        $courses = Course::query()
            ->select(['courses.id', 'courses.faculty_id', 'courses.teacher_id', 'courses.academic_year_id', 'courses.code', 'courses.name', 'courses.credit', 'courses.semester', 'courses.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->where('courses.faculty_id', Auth::user()->operator->faculty_id)
            ->where('courses.department_id', Auth::user()->operator->department_id)
            ->with(['teacher.user', 'academicYear'])
            ->paginate(request()->load ?? 10);

        return Inertia::render('Operators/Courses/Index', [
            'page_settings' => [
                'title' => 'Mata Kuliah',
                'subtitle' => "Daftar semua mata kuliah yang terdaftar di Program Studi {$operator->faculty?->name}",
            ],
            'courses' => CourseOperatorResource::collection($courses)->additional([
                'meta' => [
                    'has_pages' => $courses->hasPages(),
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
        return Inertia::render('Operators/Courses/Create', [
            'page_settings' => [
                'title' => 'Tambah Mata Kuliah',
                'subtitle' => 'Buat mata kuliah baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('operators.courses.store'),
            ],
            'teachers' => Teacher::query()
                ->select(['id', 'user_id'])
                ->whereHas('user', function($query) {
                    $query->whereHas('roles', fn($query) => $query->where('name', 'Teacher'))->orderBy('name');
                })
                ->where('faculty_id', Auth::user()->operator->faculty_id)
                ->where('department_id', Auth::user()->operator->department_id)
                ->with(['user'])
                ->get()
                ->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->user->name,
                ])
        ]);
    }

    public function store(CourseOperatorRequest $request): RedirectResponse
    {
        try{
            Course::create([
                'faculty_id' => Auth::user()->operator->faculty_id,
                'department_id' => Auth::user()->operator->department_id,
                'teacher_id' => $request->validated()['teacher_id'],
                'academic_year_id' => activeAcademicYear()->id,
                'code' => str()->random(6),
                'name' => $request->validated()['name'],
                'credit' => $request->validated()['credit'],
                'semester' => $request->validated()['semester'],
            ]);

            flashMessage(MessageType::CREATED->message('Mata kuliah'));
            return to_route('operators.courses.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message($e->getMessage()), 'error');
            return to_route('operators.courses.index');
        }
    }


    public function edit(Course $course): Response
    {
        return Inertia::render('Operators/Courses/Edit', [
            'page_settings' => [
                'title' => 'Edit Mata Kuliah',
                'subtitle' => 'Edit mata kuliah disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('operators.courses.update', $course),
            ],
            'course' => $course,
            'teachers' => Teacher::query()
                ->select(['id', 'user_id'])
                ->whereHas('user', function($query) {
                    $query->whereHas('roles', fn($query) => $query->where('name', 'Teacher'))->orderBy('name');
                })
                ->where('faculty_id', Auth::user()->operator->faculty_id)
                ->where('department_id', Auth::user()->operator->department_id)
                ->with(['user'])
                ->get()
                ->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->user->name,
                ])
        ]);
    }

    public function update(CourseOperatorRequest $request, Course $course): RedirectResponse
    {
        try{
            $course->update([
                'teacher_id' => $request->validated()['teacher_id'],
                'name' => $request->validated()['name'],
                'credit' => $request->validated()['credit'],
                'semester' => $request->validated()['semester'],
            ]);

            flashMessage(MessageType::UPDATED->message('Mata kuliah'));
            return to_route('operators.courses.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message($e->getMessage()), 'error');
            return to_route('operators.courses.index');
        }
    }

    public function destroy(Course $course): RedirectResponse
    {
        try {
            $course->delete();
            flashMessage(MessageType::DELETED->message('Mata Kuliah'));
            return to_route('operators.courses.index');
        } catch (Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.courses.index');
        }
    }
}

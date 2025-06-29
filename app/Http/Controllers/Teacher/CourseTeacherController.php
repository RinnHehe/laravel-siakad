<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\CourseScheduleResource;
use App\Http\Resources\Teacher\CourseTeacherResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Inertia\Inertia;

class CourseTeacherController extends Controller
{
    public function index(): Response
    {
        $courses = Course::query()
            ->where('teacher_id', Auth::user()->teacher->id)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['faculty', 'department', 'schedules'])
            ->paginate(request()->load ?? 9);

        return Inertia::render('Teachers/Courses/Index', [
            'page_settings' => [
                'title' => 'Mata Kuliah',
                'subtitle' => 'Menampilkan semua data mata Kuliah yang anda ampu',
                'load' => 9
            ],
            'courses' => CourseTeacherResource::collection($courses)->additional([
                'meta' => [
                    'has_pages' => $courses->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 9,
            ]
        ]);
    }

    public function show(Course $course): Response
    {
        return Inertia::render('Teachers/Courses/Show', [
            'page_settings' => [
                'title' => $course->name,
                'subtitle' => 'Menampilkan detail mata kuliah',
            ],
            'course' => new CourseScheduleResource($course->load(['faculty', 'department', 'academicYear', 'schedules']))
        ]);
    }
}

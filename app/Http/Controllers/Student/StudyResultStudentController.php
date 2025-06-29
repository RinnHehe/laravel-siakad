<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Student\StudyResultStudentResource;
use App\Models\StudyResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Inertia\Inertia;

class StudyResultStudentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $studyResults = StudyResult::query()
            ->select(['id', 'student_id', 'academic_year_id', 'gpa', 'semester', 'created_at'])
            ->where('student_id', Auth::user()->student->id)
            ->with(['grades', 'academicYear'])
            ->paginate(request()->load ?? 10);

        return Inertia::render('Students/StudyResults/Index', [
            'page_settings' => [
                'title' => 'Kartu Hasil Studi',
                'subtitle' => 'Menampilkan semua data kartu hasil studi',
            ],
            'studyResults' => StudyResultStudentResource::collection($studyResults)->additional([
                'meta' => [
                    'has_pages' => $studyResults->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'load' => 10,
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Student\StudyResultStudentResource;
use App\Models\StudyResult;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Response;
use Inertia\Inertia;

class StudyResultStudentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('checkActiveAcademicYear'),
            new Middleware('checkFeeStudent'),
        ];
    }

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

    public function download($id)
    {
        try {
            $studyResult = StudyResult::with([
                'grades.course',
                'student.department.faculty',
                'academicYear'
            ])
                ->where('student_id', Auth::user()->student->id)
                ->findOrFail($id);

            $yearName = str_replace(['/', '\\'], '-', $studyResult->academicYear->name);
            $pdf = Pdf::loadView('pdf.khs', compact('studyResult'));
            return $pdf->download('KHS-'.$yearName.'-Semester-'.$studyResult->semester.'.pdf');

        } catch (\Exception $e) {
            Log::error('Download KHS Error: ' . $e->getMessage());
            abort(500, 'Error generating PDF');
        }
    }

}

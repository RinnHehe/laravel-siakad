<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudyPlan;
use Illuminate\Http\Request;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Resources\Student\StudyPlanStudentResource;

class StudyPlanStudentController extends Controller
{
    public function index(): Response
    {
        $studyPlans = StudyPlan::query()
            ->select(['id', 'student_id', 'academic_year_id', 'status', 'created_at'])
            ->where('student_id', Auth::user()->student->id)
            ->with(['academicYear'])
            ->latest('created_at')
            ->paginate(request()->load ?? 10);

        return Inertia::render('Students/StudyPlans/Index', [
            'page_settings' => [
                'title' => 'Kartu Rencana Studi',
                'subtitle' => 'Menampilkan semua kartu rencana studi anda',
            ],
            'studyPlans' => StudyPlanStudentResource::collection($studyPlans)->additional([
                'page_settings' => [
                    'meta' => [
                        'has_pages' => $studyPlans->hasPages(),
                    ]
                ]
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ]
        ]);
    }
}

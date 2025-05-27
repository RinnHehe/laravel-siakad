<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudyPlan;
use App\Models\Fee;
use App\Enums\StudyPlanStatus;
use App\Enums\FeeStatus;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardStudentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Inertia\Response
     */
    public function __invoke(): Response
    {
        return inertia('Students/Dashboard', [
            'page_settings' => [
                'title' => 'Dashboard',
                'subtitle' => 'Menampilkan semua statistik pada platform ini.',
            ],
            'count' => [
                'study_plans_approved' => StudyPlan::query()
                    ->where('status', StudyPlanStatus::APPROVED->value)
                    ->count(),
                
                'study_plans_reject' => StudyPlan::query()
                    ->where('status', StudyPlanStatus::REJECT->value)
                    ->count(),
                
                'total_payments' => Fee::query()
                    ->where('student_id', Auth::user()->student->id)
                    ->where('status', FeeStatus::SUCCESS->value)
                    ->with('feeGroup')
                    ->get()
                    ->sum(fn($fee) => $fee->feeGroup->amount),
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Student\FeeStudentResource;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FeeStudentController extends Controller
{
    public function __invoke(): Response
    {
        $fee = Fee::query()
            ->where('student_id', Auth::user()->student->id)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->where('semester', Auth::user()->student->semester)
            ->first();

        $fees = Fee::query()
            ->select(['fees.id','fees.fee_code', 'fees.student_id', 'fees.academic_year_id', 'fees.fee_group_id', 'fees.semester', 'fees.status', 'fees.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->where('student_id', Auth::user()->student->id)
            ->with(['feeGroup', 'academicYear'])
            ->paginate(request()->load ?? 10 );

        return Inertia::render('Students/Fees/Index', [
            'page_settings' => [
                'title' => 'Pembayaran',
                'subtitle' => 'Menampilkan semua data pembayaran ukt yang tersedia',
            ],
            'fee' => $fee,
            'fees' => FeeStudentResource::collection($fees)->additional([
                'meta' => [
                    'page' => request()->page ?? 1,
                    'search' => request()->search ?? '',
                    'load' => 10,
                ]
            ])
        ]);
    }
}

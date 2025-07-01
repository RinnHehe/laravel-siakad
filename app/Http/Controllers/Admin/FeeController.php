<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\FeeResource;
use App\Models\Fee;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;

class FeeController extends Controller
{
    public function __invoke(): Response
    {
        $fees = Fee::query()
            ->select(['fees.id', 'fees.fee_code', 'fees.student_id', 'fees.fee_group_id', 'fees.academic_year_id', 'fees.semester', 'fees.status', 'fees.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['student.faculty', 'student.department', 'student.classroom', 'student.user', 'feeGroup', 'academicYear'])
            ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/Fees/Index', [
            'page_settings' => [
                'title' => 'Uang Kuliah Tunggal',
                'subtitle' => 'Menampilkan semua data uang kuliah tunggal yang tersedia pada Politeknik Kotabaru',
            ],
            'fees' => FeeResource::collection($fees)->additional([
                'meta' => [
                    'has_pages' => $fees->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],
        ]);
    }
}

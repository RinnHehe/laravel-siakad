<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AcademicYearResource;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AcademicYearController extends Controller
{
    public function index(): Response
    {
        $academicYears = AcademicYear::query()
            ->select(['id', 'name', 'start_date', 'end_date', 'semester', 'is_active', 'created_at'])
            ->filter(request()->only('search'))
            ->sorting(request()->only('field', 'direction'))
            ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/AcademicYears/Index', [
            'page_settings' => [
                'title' => 'Tahun Ajaran',
                'subtitle' => 'Menampilkan semua tahun ajaran yang tersedia pada Politeknik Negeri Kota Baru',
            ],
            'academicYears' => AcademicYearResource::collection($academicYears)->additional([
                'meta' => [
                    'has_pages' => $academicYears->hasPages(),
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

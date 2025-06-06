<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AcademicYearSemester;
use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AcademicYearsRequest;
use App\Http\Resources\Admin\AcademicYearResource;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;
use Illuminate\Http\RedirectResponse;

class AcademicYearController extends Controller
{
    public function index(): Response
    {
        $academicYears = AcademicYear::query()
            ->select(['academic_years.id', 'academic_years.name', 'academic_years.slug', 'academic_years.start_date', 'academic_years.end_date', 'academic_years.semester', 'academic_years.is_active', 'academic_years.created_at'])
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

    public function create(): Response
    {
        return Inertia::render('Admin/AcademicYears/Create', [
            'page_settings' => [
                'title' => 'Tambah Tahun Ajaran',
                'subtitle' => 'Buat tahun ajaran baru disini. Klik simpan setelah selesai.',
                'method' => 'POST',
                'action' => route('admin.academic-years.store'),
            ],
            'academicYearSemester' => AcademicYearSemester::options(),
        ]);
    }

    public function store(AcademicYearsRequest $request)
    {
        try {
            $validated = $request->validated();

            AcademicYear::create([
                'name' => $validated['name'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'semester' => $validated['semester'],
                'is_active' => $validated['is_active'],
            ]);

            session()->flash('type', 'success');
            session()->flash('message', MessageType::CREATED->message('Tahun Ajaran'));

            return Inertia::location(route('admin.academic-years.index'));

        } catch (Throwable $e) {
            return Inertia::render('Admin/AcademicYears/Create', [
                'page_settings' => [
                    'title' => 'Tambah Tahun Ajaran',
                    'subtitle' => 'Buat tahun ajaran baru disini. Klik simpan setelah selesai.',
                    'method' => 'POST',
                    'action' => route('admin.academic-years.store'),
                ],
                'academicYearSemester' => AcademicYearSemester::options(),
                'errors' => [
                    'name' => $e->getMessage()
                ]
            ]);
        }
    }

    public function edit(AcademicYear $academicYear): Response
    {
        return Inertia::render('Admin/AcademicYears/Edit', [
            'page_settings' => [
                'title' => 'Edit Tahun Ajaran',
                'subtitle' => 'Edit tahun ajaran yang sudah ada disini. Klik simpan setelah selesai.',
                'method' => 'PUT',
                'action' => route('admin.academic-years.update', $academicYear),
            ],
            'academicYear' => $academicYear,
            'academicYearSemester' => AcademicYearSemester::options(),
        ]);
    }

    public function update(AcademicYear $academicYear, AcademicYearsRequest $request)
    {
        try {
            $validated = $request->validated();

            $academicYear->update([
                'name' => $validated['name'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'semester' => $validated['semester'],
                'is_active' => $validated['is_active'],
            ]);

            session()->flash('type', 'success');
            session()->flash('message', MessageType::UPDATED->message('Tahun Ajaran'));

            return Inertia::location(route('admin.academic-years.index'));

        } catch (Throwable $e) {
            return Inertia::render('Admin/AcademicYears/Edit', [
                'page_settings' => [
                    'title' => 'Edit Tahun Ajaran',
                    'subtitle' => 'Edit tahun ajaran yang sudah ada disini. Klik simpan setelah selesai.',
                    'method' => 'PUT',
                    'action' => route('admin.academic-years.update', $academicYear),
                ],
                'academicYear' => $academicYear,
                'academicYearSemester' => AcademicYearSemester::options(),
                'errors' => [
                    'name' => $e->getMessage()
                ]
            ]);
        }
    }

    public function destroy(AcademicYear $academicYear)
    {
        try {
            $academicYear->delete();

            session()->flash('type', 'success');
            session()->flash('message', MessageType::DELETED->message('Tahun Ajaran'));

            return Inertia::location(route('admin.academic-years.index'));

        } catch (Throwable $e) {
            session()->flash('type', 'error');
            session()->flash('message', 'Gagal menghapus tahun ajaran: ' . $e->getMessage());

            return back();
        }
    }
}

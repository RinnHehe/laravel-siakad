<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FacultyRequest;
use App\Http\Resources\Admin\FacultyResource;
use App\Models\Faculty;
use App\Traits\HasFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Department;
use Throwable;

class FacultyController extends Controller
{
    use HasFile;

    public function index(): Response
    {
        $faculties = Faculty::query()
            ->select(['faculties.id', 'faculties.name', 'faculties.code', 'faculties.logo', 'faculties.slug', 'faculties.created_at'])
            ->filter(request()->only('search'))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/Faculties/Index', [
            'page_settings' => [
                'title' => 'Program Studi',
                'subtitle' => 'Menampilkan semua data program studi yang tersedia pada Politeknik Kotabaru',
                'breadcrumbs' => [
                    ['name' => 'Program Studi', 'url' => route('admin.faculties.index')]
                ]
            ],
            'faculties' => FacultyResource::collection($faculties)->additional([
                'meta' => [
                    'has_pages' => $faculties->hasPages()
                ]
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10
            ]
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Faculties/Create', [
            'page_settings' => [
                'title' => 'Tambah Program Studi',
                'subtitle' => ' Buat program studi baru, Klik simpan untuk menyimpan data program studi',
                'method' => 'POST',
                'action' => route('admin.faculties.store'),
            ]
        ]);
    }

    public function store(FacultyRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $faculty = Faculty::create([
                'name' => $validated['name'],
                'code' => str()->random(5),
                'logo' => $this->upload_file($request, 'logo', 'faculties')
            ]);

            // Buat department otomatis
            Department::create([
                'faculty_id' => $faculty->id,
                'name' => $faculty->name,
                'code' => $faculty->code,
            ]);

            flashMessage(MessageType::CREATED->message('Program Studi'));
            return to_route('admin.faculties.index');

        } catch (Throwable $e) {
            flashMessage($e->getMessage(), 'error');
            return back();
        }
    }


    public function edit(Faculty $faculty): Response
    {
        return Inertia::render('Admin/Faculties/Edit', [
            'page_settings' => [
                'title' => 'Edit Program Studi',
                'subtitle' => 'Edit program studi baru, Klik simpan untuk menyimpan data program studi',
                'method' => 'PUT',
                'action' => route('admin.faculties.update', $faculty),
            ],
            'faculty' => $faculty,
        ]);
    }

    public function update(Faculty $faculty, FacultyRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $faculty->update([
                'name' => $validated['name'],
                'logo' => $this->update_file($request, $faculty, 'logo', 'faculties')
            ]);

            // Update department yang terkait (mengambil department pertama yang terkait, jika lebih dari 1 disesuaikan logic-nya)
            $department = $faculty->departments()->first();
            if ($department) {
                $department->update([
                    'name' => $faculty->name,
                    'code' => $faculty->code,
                ]);
            } else {
                // Jika belum ada department terkait, buat baru
                Department::create([
                    'faculty_id' => $faculty->id,
                    'name' => $faculty->name,
                    'code' => $faculty->code,
                ]);
            }

            flashMessage(MessageType::UPDATED->message('Program Studi'));
            return to_route('admin.faculties.index');

        } catch (Throwable $e) {
            flashMessage($e->getMessage(), 'error');
            return back();
        }
    }


    public function destroy(Faculty $faculty): RedirectResponse
    {
        try {
            $this->delete_file($faculty, 'logo');
            $faculty->delete();

            flashMessage(MessageType::DELETED->message('Program Studi'));
            return to_route('admin.faculties.index');

        } catch (Throwable $e) {
            flashMessage($e->getMessage(), 'error');
            return back();
        }
    }
}

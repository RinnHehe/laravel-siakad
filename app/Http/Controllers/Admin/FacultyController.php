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
use Throwable;

class FacultyController extends Controller
{
    use HasFile;

    public function index(): Response
    {
        $faculties = Faculty::query()
            ->select(['id', 'name', 'code', 'slug', 'created_at'])
            ->filter(request()->only('search'))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/Faculties/Index', [
            'page_settings' => [
                'title' => 'Jurusan',
                'subtitle' => 'Menampilkan semua data jurusan yang tersedia pada Politeknik Negeri Kotabaru',
                'breadcrumbs' => [
                    ['name' => 'Jurusan', 'url' => route('admin.faculties.index')]
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
                'title' => 'Tambah Jurusan',
                'subtitle' => ' Buat jurusan baru, Klik simpan untuk menyimpan data jurusan',
                'method' => 'POST',
                'action' => route('admin.faculties.store'),
            ]
        ]);
    }

    public function store(FacultyRequest $request)
    {
        try{
            $validated = $request->validated();
            
            Faculty::create([
                'name' => $validated['name'],
                'code' => str()->random(5),
                'logo' => $this->upload_file($request, 'logo', 'faculties')
            ]);
            
            session()->flash('type', 'success');
            session()->flash('message', MessageType::CREATED->message('Jurusan'));

            return Inertia::location(route('admin.faculties.index'));
            
        } catch (Throwable $e){
            return Inertia::render('Admin/Faculties/Create', [
                'page_settings' => [
                    'title' => 'Tambah Jurusan',
                    'subtitle' => ' Buat jurusan baru, Klik simpan untuk menyimpan data jurusan',
                    'method' => 'POST',
                    'action' => route('admin.faculties.store'),
                ],
                'errors' => [
                    'name' => $e->getMessage()
                ]
            ]);
        }
    }

    public function edit(Faculty $faculty): Response
    {
        return Inertia::render('Admin/Faculties/Edit', [
            'page_settings' => [
                'title' => 'Edit Jurusan',
                'subtitle' => 'Edit jurusan baru, Klik simpan untuk menyimpan data jurusan',
                'method' => 'PUT',
                'action' => route('admin.faculties.update', $faculty),
            ],
            'faculty' => $faculty,
        ]);
    }

    public function update(Faculty $faculty, FacultyRequest $request)
    {
        try{
            $validated = $request->validated();
            
            $faculty->update([
                'name' => $validated['name'],
                'logo' => $this->update_file($request, $faculty, 'logo', 'faculties')
            ]);
            
            session()->flash('type', 'success');
            session()->flash('message', MessageType::UPDATED->message('Jurusan'));

            return Inertia::location(route('admin.faculties.index'));
            
        } catch (Throwable $e){
            return Inertia::render('Admin/Faculties/Edit', [
                'page_settings' => [
                    'title' => 'Tambah Jurusan',
                    'subtitle' => ' Buat jurusan baru, Klik simpan untuk menyimpan data jurusan',
                    'method' => 'POST',
                    'action' => route('admin.faculties.edit'),
                ],
                'errors' => [
                    'name' => $e->getMessage()
                ]
            ]);
        }
    }

    public function destroy(Faculty $faculty)
    {
        try {
            $this->delete_file($faculty, 'logo');
            $faculty->delete();

            session()->flash('type', 'success');
            session()->flash('message', MessageType::DELETED->message('Jurusan'));

            return Inertia::location(route('admin.faculties.index'));

        } catch (Throwable $e) {
            session()->flash('type', 'error');
            session()->flash('message', 'Gagal menghapus jurusan: ' . $e->getMessage());

            return back();
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClassroomRequest;
use App\Http\Resources\Admin\ClassroomResource;
use App\Models\Classroom;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Response;
use Inertia\Inertia;
use Throwable;

class ClassroomController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('validateDepartment', ['store', 'update']),
        ];
    }
    public function index(): Response
    {
        $classrooms = Classroom::query()
            ->select(['classrooms.id', 'classrooms.name', 'classrooms.faculty_id', 'classrooms.department_id', 'classrooms.academic_year_id', 'classrooms.slug', 'classrooms.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['faculty', 'department', 'academicYear'])
            ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/Classrooms/Index', [
            'page_settings' => [
                'title' => 'Kelas',
                'subtitle' => 'Menampilkan semua data kelas yang tersedia pada Politeknik Negeri Kotabaru',
            ],
            'classrooms' => ClassroomResource::collection($classrooms)->additional([
                'meta' => [
                    'has_pages' => $classrooms->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ]
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Classrooms/Create', [
            'page_settings' => [
                'title' => 'Tambah kelas',
                'subtitle' => 'Tambahkan kelas baru disini, Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.classrooms.store'),
            ],
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }

    public function store(ClassroomRequest $request)
    {
        try{
            $validated = $request->validated();

            Classroom::create([
                'faculty_id' => $validated['faculty_id'],
                'department_id' => $validated['department_id'],
                'academic_year_id' => activeAcademicYear()->id,
                'name' => $validated['name'],
            ]);

            session()->flash('type', 'success');
            session()->flash('message', MessageType::CREATED->message('Kelas'));

            return Inertia::location(route('admin.classrooms.index'));

        } catch (Throwable $e){
            return Inertia::render('Admin/Classrooms/Create', [
                'page_settings' => [
                    'title' => 'Tambah Kelas',
                    'subtitle' => 'Tambahkan kelas baru disini, Klik simpan setelah selesai',
                    'method' => 'POST',
                    'action' => route('admin.classrooms.store'),
                ],
                'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
                'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
                'errors' => [
                    'name' => $e->getMessage()
                ]
            ]);
        }
    }

    public function edit(Classroom $classroom): Response
    {
        return Inertia::render('Admin/Classrooms/Edit', [
            'page_settings' => [
                'title' => 'Edit kelas',
                'subtitle' => 'Edit kelas disini, Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.classrooms.update', $classroom),
            ],
            'classroom' => $classroom,
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }

    public function update(ClassroomRequest $request, Classroom $classroom)
    {
        try {
            $validated = $request->validated();

            $classroom->update([
                'faculty_id' => $validated['faculty_id'],
                'department_id' => $validated['department_id'],
                'name' => $validated['name'],
            ]);

            session()->flash('type', 'success');
            session()->flash('message', MessageType::UPDATED->message('Kelas'));

            return Inertia::location(route('admin.classrooms.index'));

        } catch (Throwable $e) {
            return Inertia::render('Admin/Classrooms/Edit', [
                'page_settings' => [
                    'title' => 'Edit Kelas',
                    'subtitle' => 'Edit kelas disini, Klik simpan setelah selesai',
                    'method' => 'PUT',
                    'action' => route('admin.classrooms.update', $classroom),
                ],
                'classroom' => $classroom,
                'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
                'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
                'errors' => [
                    'name' => $e->getMessage()
                ]
            ]);
        }
    }

    public function destroy(Classroom $classroom)
    {
        try {
            $classroom->delete();

            session()->flash('type', 'success');
            session()->flash('message', MessageType::DELETED->message('Kelas'));

            return Inertia::location(route('admin.classrooms.index'));

        } catch (Throwable $e) {
            session()->flash('type', 'error');
            session()->flash('message', 'Gagal menghapus Kelas: ' . $e->getMessage());

            return back();
        }
    }
}

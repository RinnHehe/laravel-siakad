<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DepartmentRequest;
use App\Http\Resources\Admin\DepartmentResource;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Throwable;

class DepartmentController extends Controller
{
    public function index(): Response
    {
        $departments = Department::query()
            ->select(['id', 'faculty_id', 'name', 'code', 'slug', 'created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['faculty'])
            ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/Departments/Index', [
            'page_settings' => [
                'title' => 'Program Studi',
                'subtitle' => 'Menampilkan semua program studi yang ada di Politeknik Negeri Kotabaru',    
                ],
            'departments' => DepartmentResource::collection($departments)->additional([
                'meta' => [
                    'has_pages' => $departments->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Departments/Create', [
            'page_settings' => [
                'title' => 'Tambah Program Studi',
                'subtitle' => 'Tambahkan program studi baru disini, Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.departments.store'),
            ],
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }

    public function store(DepartmentRequest $request)
    {
        try{
            $validated = $request->validated();
            
            Department::create([
                'faculty_id' => $validated['faculty_id'],
                'name' => $validated['name'],
                'code' => str()->random(6),
            ]);
            
            session()->flash('type', 'success');
            session()->flash('message', MessageType::CREATED->message('Program Studi'));

            return Inertia::location(route('admin.departments.index'));
            
        } catch (Throwable $e){
            return Inertia::render('Admin/Departments/Create', [
                'page_settings' => [
                    'title' => 'Tambah Program Studi',
                    'subtitle' => 'Tambahkan program studi baru disini, Klik simpan setelah selesai',
                    'method' => 'POST',
                    'action' => route('admin.departments.store'),
                ],
                'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
                'errors' => [
                    'name' => $e->getMessage()
                ]
            ]);
        }
    }
}
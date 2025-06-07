<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use App\Http\Resources\Admin\RoleResource;
use Spatie\Permission\Models\Role;
use Throwable;

class RoleController extends Controller
{
    public function index(): Response
    {
        $roles = Role::query()
            ->select('id', 'name', 'guard_name', 'created_at')
            ->when(request()->search, function ($query, $search) {
                $query->whereAny([
                    'name',
                    'guard_name',
                ], 'REGEXP', $search);
            })
            ->when(request()->field && request()->direction, fn($query) => $query->orderBy(request()->field, request()->direction))
            ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/Roles/Index', [
            'page_settings' => [
                'title' =>'Tambah Peran',
                'subtitle' => 'Menampilkan semua data peran yang tersedia pada Politeknik Negeri Kotabaru',
            ],
            'roles' => RoleResource::collection($roles)->additional([
                'meta' => [
                    'has_pages' => $roles->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
            ]
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Roles/Create', [
            'page_settings' => [
                'title' => 'Tambah Peran',
                'subtitle' => 'Buat peran baru disini.Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.roles.store'),
            ],
        ]);
    }

    public function store(RoleRequest $request)
    {
        try{
            $validated = $request->validated();

            Role::create([
                'name' => $validated['name'],
                'guard_name' => 'web',
            ]);

            session()->flash('type', 'success');
            session()->flash('message', MessageType::CREATED->message('Peran'));

            return Inertia::location(route('admin.roles.index'));

        } catch (Throwable $e){
            return Inertia::render('Admin/Roles/Create', [
                'page_settings' => [
                    'title' => 'Tambah Peran',
                    'subtitle' => ' Buat peran baru, Klik simpan untuk menyimpan data peran',
                    'method' => 'POST',
                    'action' => route('admin.roles.store'),
                ],
                'errors' => [
                    'name' => $e->getMessage()
                ]
            ]);
        }
    }

    public function edit(Role $role): Response
    {
        return Inertia::render('Admin/Roles/Edit', [
            'page_settings' => [
                'title' => 'Edit Peran',
                'subtitle' => 'Edit peran disini.Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.roles.update', $role),
            ],
            'role' => $role,
        ]);
    }

    public function update(RoleRequest $request, Role $role)
    {
        try{
            $validated = $request->validated();

            $role->update([
                'name' => $validated['name'],
            ]);

            session()->flash('type', 'success');
            session()->flash('message', MessageType::UPDATED->message('Peran'));

            return Inertia::location(route('admin.roles.index'));

        } catch (Throwable $e){
            return Inertia::render('Admin/Roles/Edit', [
                'page_settings' => [
                    'title' => 'Edit Peran',
                    'subtitle' => 'Edit peran disini. Klik simpan setelah selesai',
                    'method' => 'PUT',
                    'action' => route('admin.roles.update', $role),
                ],
                'errors' => [
                    'name' => $e->getMessage()
                ]
            ]);
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();

            session()->flash('type', 'success');
            session()->flash('message', MessageType::DELETED->message('Peran'));

            return Inertia::location(route('admin.roles.index'));

            } catch (Throwable $e) {
                session()->flash('type', 'error');
                session()->flash('message', 'Gagal menghapus peran: ' . $e->getMessage());

            return back();
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OperatorRequest;
use App\Http\Resources\Admin\OperatorResource;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Operator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;
use App\Traits\HasFile;

class OperatorController extends Controller
{
    use HasFile;
    public function index(): Response
    {
        $operators = Operator::query()
        ->select(['operators.id', 'operators.user_id', 'operators.faculty_id', 'operators.department_id', 'operators.employee_number', 'operators.created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->whereHas('user', function($query){
            $query->whereHas('roles', fn($query) => $query->where('name', 'Operator'));
        })
        ->with(['user', 'faculty', 'department'])
        ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/Operators/Index', [
            'page_settings' => [
                'title' => 'Operator',
                'subtitle' => 'Menampilkan semua data operator yang tersedia di Universitas ini.',
            ],
            'operators' => OperatorResource::collection($operators)->additional([
                'meta' => [
                    'has_pages' => $operators->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Operators/Create',[
            'page_settings' => [
                'title' => 'Tambah Operator',
                'subtitle' => 'Buat operator baru disini. Klik simpan setelah selesai.',
                'method' => 'POST',
                'action' => route('admin.operators.store'),
            ],
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item)=>
                [
                    'value' => $item->id,
                    'label' => $item->name,
                ]
            ),
            'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item)=>
                [
                    'value' => $item->id,
                    'label' => $item->name,
                ]
            ),
        ]);
    }
    public function store(OperatorRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'password' => Hash::make($request->validated('password')),
                'avatar' => $this->upload_file($request, 'avatar', 'users'),
            ]);

            $user->operator()->create([
                'faculty_id' => $request->validated('faculty_id'),
                'department_id' => $request->validated('department_id'),
                'employee_number' => $request->validated('employee_number'),
            ]);

            $user->assignRole('Operator');
            DB::commit();

            session()->flash('type', 'success');
            session()->flash('message', MessageType::CREATED->message('Operator'));

            return Inertia::location(route('admin.operators.index'));

        } catch (Throwable $e){
            DB::rollBack();
            return back()->withErrors([
                'name' => $e->getMessage(),
            ])->withInput();
        }
    }

    public function edit(Operator $operator): Response
    {
        return Inertia::render('Admin/Operators/Edit',[
            'page_settings' => [
                'title' => 'Edit Operator',
                'subtitle' => 'Edit operator baru disini. Klik simpan setelah selesai.',
                'method' => 'PUT',
                'action' => route('admin.operators.update', $operator),
            ],
            'operator' => $operator->load('user'),
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item)=>
                [
                    'value' => $item->id,
                    'label' => $item->name,
                ]
            ),
            'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item)=>
                [
                    'value' => $item->id,
                    'label' => $item->name,
                ]
            ),
        ]);

    }
    public function update(Operator $operator, OperatorRequest $request)
    {
        try {
            DB::beginTransaction();
            $operator->update([
                'faculty_id' => $request->validated('faculty_id'),
                'department_id' => $request->validated('department_id'),
                'employee_number' => $request->validated('employee_number'),
            ]);
            $operator->user()->update([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'password' => $request->validated('password') ? Hash::make($request->validated('password')) : $operator->user->password,
                'avatar' => $this->update_file($request, $operator->user, 'avatar', 'users'),
            ]);
            DB::commit();
            session()->flash('type', 'success');
            session()->flash('message', MessageType::UPDATED->message('Operator'));
            return Inertia::location(route('admin.operators.index'));
        } catch (Throwable $e){
            DB::rollBack();
            return back()->withErrors([
                'name' => $e->getMessage(),
            ])->withInput();
        }
    }
    public function destroy(Operator $operator)
    {
        try {
            $this->delete_file($operator->user, 'avatar');
            $operator->delete();

            session()->flash('type', 'success');
            session()->flash('message', MessageType::DELETED->message('Operator'));

            return Inertia::location(route('admin.operators.index'));

        } catch (Throwable $e) {
            session()->flash('type', 'error');
            session()->flash('message', 'Gagal menghapus operator: ' . $e->getMessage());

            return back();
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeacherRequest;
use App\Http\Resources\Admin\TeacherResource;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Teacher;
use App\Models\User;
use App\Traits\HasFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class TeacherController extends Controller
{
    use HasFile;
    public function index(Request $request): Response
    {
        $teachers = Teacher::query()
            ->select(['teachers.id', 'teachers.user_id', 'teachers.faculty_id', 'teachers.department_id', 'teachers.teacher_number', 'teachers.academic_title', 'teachers.created_at'])
            ->filter($request->only(['search']))
            ->sorting($request->only(['field', 'direction']))
            ->with(['user', 'faculty', 'department'])
            ->paginate($request->input('load', 10));

        return Inertia::render('Admin/Teachers/Index', [
            'page_settings' => [
                'title' => 'Dosen',
                'subtitle' => 'Menampilkan semua data dosen yang tersedia di Universitas ini.',
            ],
            'teachers' => TeacherResource::collection($teachers)->additional([
                'meta' => [
                    'has_pages' => $teachers->hasPages(),
                ],
            ]),
            'state' => [
                'page' => $request->input('page', 1),
                'search' => $request->input('search', ''),
                'load' => $request->input('load', 10),
            ],
        ]);
    }

    public function create(): Response
    {
        return inertia('Admin/Teachers/Create',[
            'page_settings' => [
                'title' => 'Tambah Dosen',
                'subtitle' => 'Buat dosen baru disini. Klik simpan setelah selesai.',
                'method' => 'POST',
                'action' => route('admin.teachers.store'),
            ],
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item)=>[
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item)=>[
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    
    }
    public function store(TeacherRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar' => $this->upload_file($request, 'avatar', 'users'),
            ]);

            $user->teacher()->create([
                'faculty_id' => $request->faculty_id,
                'department_id' => $request->department_id,
                'teacher_number' => $request->teacher_number,
                'academic_title' => $request->academic_title,
            ]);

            $user->assignRole('Teacher');
            DB::commit();

            session()->flash('type', 'success');
            session()->flash('message', MessageType::CREATED->message('Dosen'));

            return Inertia::location(route('admin.teachers.index'));

        } catch (Throwable $e){
            DB::rollBack();
            return back()->withErrors([
                'name' => $e->getMessage(),
            ])->withInput();
        }
    }

    public function edit(Teacher $teacher): Response
    {
        return inertia('Admin/Teachers/Edit',[
            'page_settings' => [
                'title' => 'Edit Dosen',
                'subtitle' => 'Edit dosen baru disini. Klik simpan setelah selesai.',
                'method' => 'PUT',
                'action' => route('admin.teachers.update', $teacher),
            ],
            'teacher' => $teacher->load('user'),
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item)=>[
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item)=>[
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    
    }
    public function update(Teacher $teacher, TeacherRequest $request)
    {
        try {
            DB::beginTransaction();
            $teacher->update([
                'faculty_id' => $request->faculty_id,
                'department_id' => $request->department_id,
                'teacher_number' => $request->teacher_number,
                'academic_title' => $request->academic_title,
            ]);
            $teacher->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $teacher->user->password,
                'avatar' => $this->update_file($request, $teacher->user, 'avatar', 'users'),
            ]);
            DB::commit();
            session()->flash('type', 'success');
            session()->flash('message', MessageType::UPDATED->message('Dosen'));
            return Inertia::location(route('admin.teachers.index'));
        } catch (Throwable $e){
            DB::rollBack();
            return back()->withErrors([
                'name' => $e->getMessage(),
            ])->withInput();
        }
    }
    public function destroy(Teacher $teacher)
    {
        try {
            $this->delete_file($teacher->user, 'avatar');
            $teacher->delete();

            session()->flash('type', 'success');
            session()->flash('message', MessageType::DELETED->message('Dosen'));

            return Inertia::location(route('admin.teachers.index'));

        } catch (Throwable $e) {
            session()->flash('type', 'error');
            session()->flash('message', 'Gagal menghapus dosen: ' . $e->getMessage());

            return back();
        }
    }
}

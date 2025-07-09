<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\TeacherOperatorRequest;
use App\Http\Resources\Operator\TeacherOperatorResource;
use Illuminate\Http\RedirectResponse;
use App\Models\Teacher;
use App\Models\User;
use App\Traits\HasFile;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class TeacherOperatorController extends Controller
{
    use HasFile;
    public function index(): Response
    {
        $operator = Auth::user()->operator;

        $teachers = Teacher::query()
        ->select(['teachers.id', 'teachers.user_id', 'teachers.faculty_id', 'teachers.department_id', 'teachers.teacher_number', 'teachers.academic_title', 'teachers.created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->whereHas('user', function($query){
            $query->whereHas('roles', fn($query) => $query->where('name','Teacher'));
        })
        ->where('teachers.faculty_id', Auth::user()->operator->faculty_id)
        ->where('teachers.department_id', Auth::user()->operator->department_id)
        ->with(['user'])
        ->paginate(request()->load ?? 10);

        return Inertia::render('Operators/Teachers/Index', [
            'page_settings' => [
                'title' => 'Dosen',
                'subtitle' => "Daftar semua dosen yang terdaftar di Program Studi {$operator->faculty?->name}",
            ],
            'teachers' => TeacherOperatorResource::collection($teachers)->additional([
                'meta' => [
                    'has_pages' => $teachers->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()-> page ?? 1,
                'search' => request()-> search ?? '',
                'load' => 10,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Operators/Teachers/Create', [
            'page_settings' => [
                'title' => 'Tambah Dosen',
                'subtitle' => 'Tambahkan data dosen baru disini. Klik simpan setelah selesai.',
                'method' => 'POST',
                'action' => route('operators.teachers.store'),
            ],
        ]);
    }

    public function store(TeacherOperatorRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'password' => Hash::make($request->validated('password')),
                'avatar' => $this->upload_file($request, 'avatar', 'users'),
            ]);

            $user->teacher()->create([
                'faculty_id' => Auth::user()->operator->faculty_id,
                'department_id' => Auth::user()->operator->department_id,
                'teacher_number' => $request->validated('teacher_number'),
                'academic_title' => $request->validated('academic_title'),
            ]);

            $user->assignRole('Teacher');
            DB::commit();

            flashMessage(MessageType::CREATED->message('Dosen'));
            return to_route('operators.teachers.index');

        } catch (Throwable $e){
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.teachers.index');
        }
    }

    public function edit(Teacher $teacher): Response
    {
        return Inertia::render('Operators/Teachers/Edit', [
            'page_settings' => [
                'title' => 'Edit Dosen',
                'subtitle' => 'Edit data dosen disini. Klik simpan setelah selesai.',
                'method' => 'PUT',
                'action' => route('operators.teachers.update', $teacher),
            ],
            'teacher' => $teacher->load('user'),
        ]);
    }

    public function update(TeacherOperatorRequest $request, Teacher $teacher): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $teacher->update([
                'teacher_number' => $request->validated('teacher_number'),
                'academic_title' => $request->validated('academic_title'),
            ]);

            $teacher->user()->update([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'password' => $request->validated('password') ? Hash::make($request->validated('password')) : $teacher->user->password,
                'avatar' => $this->update_file($request, $teacher->user, 'avatar', 'users'),
            ]);

            DB::commit();

            flashMessage(MessageType::UPDATED->message('Dosen'));
            return to_route('operators.teachers.index');

        } catch (Throwable $e){
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.teachers.index');
        }
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        try {
            $this->delete_file($teacher->user, 'avatar');
            $teacher->user()->delete();
            $teacher->delete();

            DB::commit();

            flashMessage(MessageType::DELETED->message('Dosen'));
            return to_route('operators.teachers.index');

        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.teachers.index');
        }
    }
}

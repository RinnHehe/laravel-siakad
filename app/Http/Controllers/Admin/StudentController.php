<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentRequest;
use App\Http\Resources\Admin\StudentResource;
use App\Models\Classroom;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\FeeGroup;
use App\Models\Student;
use App\Models\User;
use App\Traits\HasFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Response;
use Inertia\Inertia;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StudentController extends Controller implements HasMiddleware
{
    use HasFile;
    public static function middleware(): array
    {
        return [
            new Middleware('validateDepartment', ['store', 'update']),
        ];
    }
    public function index(): Response
    {
        $students = Student::query()
            ->select(['students.id', 'students.user_id', 'students.faculty_id', 'students.department_id', 'students.classroom_id', 'students.student_number', 'students.fee_group_id', 'students.semester', 'students.batch', 'students.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['user', 'faculty', 'department', 'classroom', 'feeGroup'])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', fn ($query) => $query->where('name', 'student'));
            })
            ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/Students/Index', [
            'page_settings' => [
                'title' => 'Mahasiswa',
                'subtitle' => 'Daftar semua mahasiswa yang terdaftar di Politeknik Negeri Kotabaru',
                'load' => request()->load ?? 10,
            ],

            'students' => StudentResource::collection($students)->additional([
                'meta' => [
                    'has_pages' => $students->hasPages(),
                    'search' => request()->search ?? '',
                    'load' => request()->load ?? 10,
                ],
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Students/Create', [
            'page_settings' => [
                'title' => 'Mahasiswa',
                'subtitle' => 'Tambah mahasiswa baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.students.store'),
            ],
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'feeGroups' => FeeGroup::query()->select(['id', 'group', 'amount'])->orderBy('group')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => 'Golongan ' . $item->group . ' - ' . number_format($item->amount, 0, ',', '.'),
            ]),
            'classrooms' => Classroom::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }

    public function store(StudentRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'avatar' => $this->upload_file($request, 'avatar', 'users'),
            ]);

            $user->student()->create([
                'faculty_id' => $validated['faculty_id'],
                'department_id' => $validated['department_id'],
                'classroom_id' => $validated['classroom_id'],
                'fee_group_id' => $validated['fee_group_id'],
                'student_number' => $validated['student_number'],
                'semester' => $validated['semester'],
                'batch' => $validated['batch'],
            ]);

            $user->assignRole('Student');

            DB::commit();

            session()->flash('type', 'success');
            session()->flash('message', MessageType::CREATED->message('Mahasiswa'));

            return Inertia::location(route('admin.students.index'));

        } catch (Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'name' => $e->getMessage(),
            ])->withInput();
        }
    }

    public function edit(Student $student): Response
    {
        return Inertia::render('Admin/Students/Edit', [
            'page_settings' => [
                'title' => 'Edit Mahasiswa',
                'subtitle' => 'Edit mahasiswa disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.students.update', $student),
            ],
            'student' => $student->load('user'),
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departments' => Department::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'feeGroups' => FeeGroup::query()->select(['id', 'group', 'amount'])->orderBy('group')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => 'Golongan ' . $item->group . ' - ' . number_format($item->amount, 0, ',', '.'),
            ]),
            'classrooms' => Classroom::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }

    public function update(StudentRequest $request, Student $student)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();

            $student->user->update([
                'faculty_id' => $validated['faculty_id'],
                'department_id' => $validated['department_id'],
                'fee_group_id' => $validated['fee_group_id'],
                'classroom_id' => $validated['classroom_id'],
                'student_number' => $validated['student_number'],
                'semester' => $validated['semester'],
                'batch' => $validated['batch'],
            ]);

            $student->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'] ? Hash::make($validated['password']) : $student->user->password,
                'avatar' => $this->upload_file($request, 'avatar', 'users'),
            ]);

            DB::commit();

            session()->flash('type', 'success');
            session()->flash('message', MessageType::UPDATED->message('Mahasiswa'));

            return Inertia::location(route('admin.students.index'));

        } catch (Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'name' => $e->getMessage(),
            ])->withInput();
        }
    }

    public function destroy(Student $student)
    {
        try {
            $this->delete_file($student->user, 'avatar');
            $student->delete();

            session()->flash('type', 'success');
            session()->flash('message', MessageType::DELETED->message('Mahasiswa'));

            return Inertia::location(route('admin.students.index'));

        } catch (Throwable $e) {
            session()->flash('type', 'error');
            session()->flash('message', 'Gagal menghapus mahasiswa: ' . $e->getMessage());

            return back();
        }
    }
}


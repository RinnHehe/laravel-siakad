<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\StudentOperatorRequest;
use App\Http\Resources\Operator\StudentOperatorResource;
use App\Models\Classroom;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\FeeGroup;
use App\Models\Student;
use App\Models\User;
use App\Traits\HasFile;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class StudentOperatorController extends Controller
{
    use HasFile;
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

            $faculty_name = Auth::user()->operator->faculty?->name;
            $department_name = Auth::user()->operator->department?->name;

            return Inertia::render('Operators/Students/Index', [
                'page_settings' => [
                    'title' => 'Mahasiswa',
                    'subtitle' => "Daftar semua mahasiswa yang terdaftar di Jurusan {$faculty_name} dan program studi {$department_name}",
                    'load' => request()->load ?? 10,
                ],
                'students' => StudentOperatorResource::collection($students)->additional([
                    'meta' => [
                        'has_pages' => $students->hasPages(),
                        
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
        return Inertia::render('Operators/Students/Create', [
            'page_settings' => [
                'title' => 'Mahasiswa',
                'subtitle' => 'Tambah mahasiswa baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('operators.students.store'),
            ],
            
            'feeGroups' => FeeGroup::query()->select(['id', 'group', 'amount'])->orderBy('group')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => 'Golongan ' . $item->group . ' - ' . number_format($item->amount, 0, ',', '.'),
            ]),
            'classrooms' => Classroom::query()->select(['id', 'name'])->orderBy('name')
            ->where('faculty_id', Auth::user()->operator->faculty_id)
            ->where('department_id', Auth::user()->operator->department_id)
            ->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }

    public function store(StudentOperatorRequest $request)
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

            return Inertia::location(route('operators.students.index'));

        } catch (Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'name' => $e->getMessage(),
            ])->withInput();
        }
    }

    public function edit(Student $student): Response
    {
        return Inertia::render('Operators/Students/Edit', [
            'page_settings' => [
                'title' => 'Edit Mahasiswa',
                'subtitle' => 'Edit mahasiswa disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('operators.students.update', $student),
            ],
            'student' => $student->load('user'),
            'feeGroups' => FeeGroup::query()->select(['id', 'group', 'amount'])->orderBy('group')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => 'Golongan ' . $item->group . ' - ' . number_format($item->amount, 0, ',', '.'),
            ]),
            'classrooms' => Classroom::query()->select(['id', 'name'])->orderBy('name')
            ->where('faculty_id', Auth::user()->operator->faculty_id)
            ->where('department_id', Auth::user()->operator->department_id)
            ->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }

    public function update(StudentOperatorRequest $request, Student $student)
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

            return Inertia::location(route('operators.students.index'));

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

            return Inertia::location(route('operators.students.index'));

        } catch (Throwable $e) {
            session()->flash('type', 'error');
            session()->flash('message', 'Gagal menghapus mahasiswa: ' . $e->getMessage());

            return back();
        }
    }
}

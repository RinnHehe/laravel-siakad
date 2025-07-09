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
use Illuminate\Http\RedirectResponse;
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
        $operator = Auth::user()->operator;

        $students = Student::query()
            ->select(['students.id', 'students.user_id', 'students.faculty_id', 'students.department_id', 'students.classroom_id', 'students.student_number', 'students.fee_group_id', 'students.semester', 'students.batch', 'students.created_at'])
            ->where('faculty_id', $operator->faculty_id)
            ->where('department_id', $operator->department_id)
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['user', 'faculty', 'department', 'classroom', 'feeGroup'])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', fn ($query) => $query->where('name', 'student'));
            })
            ->paginate(request()->load ?? 10);

        return Inertia::render('Operators/Students/Index', [
            'page_settings' => [
                'title' => 'Mahasiswa',
                'subtitle' => "Daftar semua mahasiswa yang terdaftar di Program Studi {$operator->faculty?->name}",
            ],
            'students' => StudentOperatorResource::collection($students)->additional([
                'meta' => [
                    'has_pages' => $students->hasPages(),
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
        $operator = Auth::user()->operator;

        return Inertia::render('Operators/Students/Create', [
            'page_settings' => [
                'title' => 'Mahasiswa',
                'subtitle' => 'Tambah mahasiswa baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('operators.students.store'),
            ],
            'faculty_id' => $operator->faculty_id,
            'department_id' => $operator->department_id,
            'feeGroups' => FeeGroup::query()
                ->select(['id', 'group', 'amount'])
                ->orderBy('group')
                ->get()
                ->map(fn($item) => [
                    'value' => $item->id,
                    'label' => 'Golongan ' . $item->group . ' - ' . number_format($item->amount, 0, ',', '.'),
                ]),
            'classrooms' => Classroom::query()
                ->select(['id', 'name'])
                ->where('faculty_id', $operator->faculty_id)
                ->where('department_id', $operator->department_id)
                ->orderBy('name')
                ->get()
                ->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
        ]);
    }

    public function store(StudentOperatorRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'password' => Hash::make($request->validated('password')),
                'avatar' => $this->upload_file($request, 'avatar', 'users'),
            ]);

            $user->student()->create([
                'faculty_id' => Auth::user()->operator->faculty_id,
                'department_id' => Auth::user()->operator->department_id,
                'classroom_id' => $request->validated('classroom_id'),
                'fee_group_id' => $request->validated('fee_group_id'),
                'student_number' => $request->validated('student_number'),
                'semester' => $request->validated('semester'),
                'batch' => $request->validated('batch'),
            ]);

            $user->assignRole('Student');

            DB::commit();
            flashMessage(MessageType::CREATED->message('Mahasiswa'), 'success');
            return to_route('operators.students.index');

        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.students.index');
        }
    }

    public function edit(Student $student): Response
    {
        $operator = Auth::user()->operator;

        return Inertia::render('Operators/Students/Edit', [
            'page_settings' => [
                'title' => 'Edit Mahasiswa',
                'subtitle' => 'Edit mahasiswa disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('operators.students.update', $student),
            ],
            'student' => $student->load('user'),
            'faculty_id' => $operator->faculty_id,
            'department_id' => $operator->department_id,
            'feeGroups' => FeeGroup::query()
                ->select(['id', 'group', 'amount'])
                ->orderBy('group')
                ->get()
                ->map(fn($item) => [
                    'value' => $item->id,
                    'label' => 'Golongan ' . $item->group . ' - ' . number_format($item->amount, 0, ',', '.'),
                ]),
            'classrooms' => Classroom::query()
                ->select(['id', 'name'])
                ->where('faculty_id', $operator->faculty_id)
                ->where('department_id', $operator->department_id)
                ->orderBy('name')
                ->get()
                ->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
        ]);
    }

    public function update(StudentOperatorRequest $request, Student $student): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $student->update([
                'faculty_id' => Auth::user()->operator->faculty_id,
                'department_id' => Auth::user()->operator->department_id,
                'classroom_id' => $request->validated('classroom_id'),
                'fee_group_id' => $request->validated('fee_group_id'),
                'student_number' => $request->validated('student_number'),
                'semester' => $request->validated('semester'),
                'batch' => $request->validated('batch'),
            ]);

            $student->user->update([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'password' => $request->validated('password') ? Hash::make($request->validated('password')) : $student->user->password,
                'avatar' => $this->upload_file($request, 'avatar', 'users'),
            ]);

            DB::commit();
            flashMessage(MessageType::UPDATED->message('Mahasiswa'), 'success');
            return to_route('operators.students.index');

        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.students.index');
        }
    }

    public function destroy(Student $student): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Get user reference before deleting student
            $user = $student->user;

            // Delete student record
            $student->delete();

            // Delete associated user and their avatar
            if ($user) {
                $this->delete_file($user, 'avatar');
                $user->delete();
            }

            DB::commit();
            flashMessage(MessageType::DELETED->message('Mahasiswa'), 'success');
            return to_route('operators.students.index');

        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.students.index');
        }
    }
}

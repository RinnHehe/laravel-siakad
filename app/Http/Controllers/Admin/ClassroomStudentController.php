<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ClassroomStudentResource;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Throwable;

class ClassroomStudentController extends Controller
{
    public function index(Classroom $classroom): Response
    {
        $classroomStudents = Student::query()
            ->select(['id', 'user_id', 'classroom_id', 'student_number', 'created_at'])
            ->where('classroom_id', $classroom->id)
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', fn($query) => $query->where('name', 'Student'));
            })
            ->orderBy('student_number')
            ->with(['user'])
            ->paginate(10);

        return Inertia::render('Admin/Classrooms/Students/Index', [
            'page_settings' => [
                'title' => "Kelas {$classroom->name}",
                'subtitle' => 'Menampilkan semua siswa kelas ' . $classroom->name,
                'method' => 'PUT',
                'action' => route('admin.classroom-students.sync', $classroom),
            ],
            'students' => Student::query()
                ->select(['id', 'user_id', 'faculty_id', 'department_id', 'classroom_id'])
                ->whereHas('user', function ($query) {
                    $query->whereHas('roles', fn($query) => $query->select(['id', 'name'])->where('name', 'Student'))->orderBy('name');
                })
                ->where('faculty_id', $classroom->faculty_id)
                ->where('department_id', $classroom->department_id)
                ->whereNull('classroom_id')
                ->get()
                ->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->user->name,
                ]),
            'classroomStudents' => ClassroomStudentResource::collection($classroomStudents),
            'classroom' => $classroom,
        ]);
    }

    public function sync(Classroom $classroom, Request $request): RedirectResponse
    {
        try {
            Student::where('id', $request->student)->update([
                'classroom_id' => $classroom->id
            ]);

            flashMessage("Berhasil menambahkan siswa ke kelas {$classroom->name}");
            return to_route('admin.classroom-students.index', $classroom);
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.classroom-students.index', $classroom);
        }
    }

    public function destroy(Classroom $classroom, Student $student): RedirectResponse
    {
        try {
            $student->update([
                'classroom_id' => null
            ]);
            flashMessage("Berhasil menghapus siswa dari kelas {$classroom->name}");
            return to_route('admin.classroom-students.index', $classroom);
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.classroom-students.index', $classroom);
        }
    }

}


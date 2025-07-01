<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\ClassroomOperatorRequest;
use App\Http\Resources\Operator\ClassroomOperatorResource;
use App\Models\Classroom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ClassroomOperatorController extends Controller
{
    public function index() : Response
    {
        $operator = Auth::user()->operator;

        $classrooms = Classroom::query()
            ->select(['id', 'faculty_id', 'department_id', 'academic_year_id', 'name', 'slug', 'created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->where('faculty_id', $operator->faculty_id)
            ->where('department_id', Auth::user()->operator->department_id)
            ->with (['academicYear'])
            ->paginate(request()->load ?? 10);

        return Inertia::render('Operators/Classrooms/Index', [
            'page_settings' => [
                'title' => 'Kelas',
                'subtitle' => "Daftar semua kelas yang terdaftar di Jurusan {$operator->faculty?->name} dan program studi {$operator->department?->name}",
            ],
            'classrooms' => ClassroomOperatorResource::collection($classrooms)->additional([
                'meta' => [
                    'has_pages' => $classrooms->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'load' => 10,
                'search' => request()->search ?? '',
            ],
        ]);
    }

    public function create() : Response
    {
        return Inertia::render('Operators/Classrooms/Create', [
            'page_settings' => [
                'title' => 'Tambah Kelas',
                'subtitle' => 'Buat kelas baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('operators.classrooms.store'),
            ],
        ]);
    }

    public function store(ClassroomOperatorRequest $request): RedirectResponse
    {
        try {
            Classroom::create([
                'faculty_id' => Auth::user()->operator->faculty_id,
                'department_id' => Auth::user()->operator->department_id,
                'academic_year_id' => activeAcademicYear()->id,
                'name' => $request->validated()['name'],
            ]);

            flashMessage(MessageType::CREATED->message('Kelas'));
            return to_route('operators.classrooms.index');

        } catch (Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.classrooms.index');
        }
    }

    public function edit(Classroom $classroom) : Response
    {
        return Inertia::render('Operators/Classrooms/Edit', [
            'page_settings' => [
                'title' => 'Edit Kelas',
                'subtitle' => 'Edit kelas disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('operators.classrooms.update', $classroom),
            ],
            'classroom' => $classroom->load(['academicYear']),
        ]);
    }

    public function update(ClassroomOperatorRequest $request, Classroom $classroom): RedirectResponse
    {
        try {
            $classroom->update([
                'name' => $request->validated()['name'],
            ]);

            flashMessage(MessageType::UPDATED->message('Kelas'));
            return to_route('operators.classrooms.index');

        } catch (Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.classrooms.index');
        }
    }

    public function destroy(Classroom $classroom): RedirectResponse
    {
        try {
            $classroom->delete();
            flashMessage(MessageType::DELETED->message('Kelas'));
            return to_route('operators.classrooms.index');
        } catch (Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.classrooms.index');
        }
    }
}



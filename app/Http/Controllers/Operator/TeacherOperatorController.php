<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\TeacherOperatorRequest;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeacherOperatorController extends Controller
{
    public function index(): Response
    {
        $teachers = Teacher::query()
        ->select(['teachers.id', 'teachers.user_id', 'teachers.faculty_id', 'teachers.department_id', 'teachers.teacher_number', 'teachers.academic_title', 'teachers.created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->whereHas('user', function($query){
            $query->whereHas('roles', fn($query) => $query->where('name','Teacher'));
        })
        ->where('teachers.faculty_id', auth()->user()->operator->faculty_id)
        ->where('teachers.department_id', auth()->user()->operator->department_id)
        ->with(['user'])
        ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/Teachers/Index', [
            'page_settings' => [
                'title' => 'Dosen',
                'subtitle' => 'Menampilkan semua data dosen yang tersedia di Universitas ini.',
            ],
            'teachers' => TeacherOperatorRequest::collection($teachers)->additional([
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
}

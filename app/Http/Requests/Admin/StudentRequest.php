<?php

namespace App\Http\Requests\Admin;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore(request()->student?->user_id)],
            'password' => [Rule::when(request()->route()->named('admin.students.store'), ['required', 'string', 'min:8', 'max:255']),
                          Rule::when(request()->route()->named('admin.students.update'), ['nullable', 'string', 'min:8', 'max:255'])],
            'faculty_id' => ['required', 'exists:faculties,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'fee_group_id' => ['required', 'exists:fee_groups,id'],
            'student_number' => ['required', 'string', 'max:13'],
            'semester' => ['required', 'integer'],
            'batch' => ['required', 'integer'],
            'avatar' => ['nullable', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'faculty_id' => 'Jurusan',
            'department_id' => 'Program Studi',
            'fee_group_id' => 'Golongan UKT',
            'student_number' => 'Nomor Induk Mahasiswa',
            'semester' => 'Semester',
            'batch' => 'Tahun Angkatan',
            'classroom_id' => 'Kelas',
        ];
    }
}

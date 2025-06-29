<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CourseClassroomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Teacher');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attendances' => ['array'],
            'attendances.*.student_id' => ['required', 'exists:students,id'],
            'attendances.*.section' => ['required', 'integer', 'min:1', 'max:12'],
            'attendances.*.status' => ['required', 'boolean'],
            'grades' => ['array'],
            'grades.*.student_id' => ['required', 'exists:students,id'],
            'grades.*.category' => ['required', 'in:tugas,uts,uas'],
            'grades.*.section' => ['nullable', 'integer', 'min:1', 'max:10'],
            'grades.*.grade' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'attendances.*.student_id' => 'ID Mahasiswa',
            'attendances.*.section' => 'Pertemuan',
            'attendances.*.status' => 'Status Kehadiran',
            'grades.*.student_id' => 'ID Mahasiswa',
            'grades.*.category' => 'Kategori Nilai',
            'grades.*.section' => 'Bagian',
            'grades.*.grade' => 'Nilai',
        ];
    }
}

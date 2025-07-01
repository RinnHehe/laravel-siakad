<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CourseRequest extends FormRequest
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
            'faculty_id' => ['required', 'exists:faculties,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'credit' => ['required', 'integer'],
            'semester' => ['required', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'faculty_id' => 'Jurusan',
            'department_id' => 'Program Studi',
            'teacher_id' => 'Dosen',
            'name' => 'Nama',
            'credit' => 'Satuan Kredit Semester (SKS)',
            'semester' => 'Semester',
        ];
    }
}

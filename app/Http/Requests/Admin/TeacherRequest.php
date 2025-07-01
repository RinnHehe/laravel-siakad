<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use function auth;

class TeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user() && Auth::user()->hasRole('Admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $teacher = request()->route('teacher');

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($teacher?->user_id),
            ],
            'password' => request()->method() === 'POST' ? [
                'required',
                'min:8',
                'max:255',
            ] : [
                'nullable',
                'min:8',
                'max:255',
            ],
            'faculty_id' => [
                'required',
                'exists:faculties,id',
            ],
            'department_id' => [
                'required',
                'exists:departments,id',
            ],
            'teacher_number' => [
                'required',
                'string',
                'max:10',
                Rule::unique('teachers')->ignore($teacher),
            ],
            'academic_title' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'avatar' => [
                'nullable',
                'mimes:png,jpg,jpeg,webp',
            ],
        ];
    }
    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Password',
            'faculty_id' => 'Fakultas',
            'department_id' => 'Program Studi',
            'teacher_number' => 'Nomor Induk Dosen',
            'academic_title' => 'Jabatan Akademik',
            'avatar' => 'Avatar',
        ];
    }
}

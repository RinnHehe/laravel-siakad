<?php

namespace App\Http\Requests\Operator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeacherOperatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Operator');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
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
                Rule::unique('users')->ignore(request()->route('teacher')?->user_id),
            ],
            'password'=> Rule::when(request()->routeIs('operators.teachers.store'),[
                'required',
                'min:8',
                'max:255',
            ],[
                'nullable',
                'min:8',
                'max:255',
            ]),
            'teacher_number'=> [
                'required',
                'string',
                'max:10',
            ],
            'academic_title'=> [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'avatar'=> [
                'nullable',
                'mimes:png,jpg,jpeg,webp',
                'max:2048',
            ],
        ];
    }
    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Password',
            'teacher_number' => 'Nomor Induk Dosen',
            'academic_title' => 'Jabatan Akademik',
        ];
    }
}

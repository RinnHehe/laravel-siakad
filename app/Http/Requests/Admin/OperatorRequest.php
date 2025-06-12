<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OperatorRequest extends FormRequest
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
                Rule::unique('users')->ignore($this->operator?->user_id ?? null),
            ],
            'password' => [
                $this->routeIs('admin.operators.store') ? 'required' : 'nullable',
                'min:8',
                'max:255',
            ],
            'faculty_id'=> [
                'required',
                'exists:faculties,id',
            ],
            'department_id'=> [
                'required',
                'exists:departments,id',
            ],
            'employee_number'=> [
                'required',
                'string',
                'max:10',
            ],
            'avatar'=> [
                'nullable',
                'mimes:png,jpg,jpeg,webp',
                'max:2048'
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
            'avatar' => 'Avatar',
            'employee_number' => 'Nomor Induk Karyawan',
        ];
    }
}

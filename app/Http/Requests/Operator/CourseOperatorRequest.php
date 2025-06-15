<?php

namespace App\Http\Requests\Operator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class CourseOperatorRequest extends FormRequest
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
            'teacher_id' => ['required', 'exists:teachers,id'],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'credit' => ['required', 'integer'],
            'semester' => ['required', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'teacher_id' => 'Dosen',
            'name' => 'Nama',
            'credit' => 'Satuan Kredit Semester',
            'semester' => 'Semester',
        ];
    }
}

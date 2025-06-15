<?php

namespace App\Http\Requests\Operator;

use App\Enums\ScheduleDay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ScheduleOperatorRequest extends FormRequest
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
            'course_id' => ['required', 'exists:courses,id'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'day_of_week' => ['required', new Enum(ScheduleDay::class)],
            'quota' => ['required', 'integer'],
        ];
    }
    public function attributes(): array{
        return [
            'course_id' => 'Mata Kuliah',
            'classroom_id' => 'Kelas',
            'start_time' => 'Waktu Mulai',
            'end_time' => 'Waktu Berakhir',
            'day_of_week' => 'Hari',
            'quota' => 'Kuota',
        ];
    }
}

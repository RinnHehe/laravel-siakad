<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FeeGroupRequest extends FormRequest
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
            'group' => ['required', 'int', Rule::unique('fee_groups', 'group')->ignore(request()->route('fee_group'))],
            'amount' => ['required', 'numeric'],
        ];
    }

    public function attributes(): array
    {
        return [
            'group' => 'Golongan UKT',
            'amount' => 'Jumlah',
        ];
    }
}

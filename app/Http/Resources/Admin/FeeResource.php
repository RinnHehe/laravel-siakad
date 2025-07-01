<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fee_code' => $this->fee_code,
            'semester' => $this->semester,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'student' => $this->whenLoaded('student', [
                'id' => $this->student?->id,
                'student_number' => $this->student?->student_number,
                'name' => $this->student?->user?->name,
                'faculty' => $this->student?->faculty?->name,
                'department' => $this->student?->department?->name,
                'classroom' => $this->student?->classroom?->name,
            ]),
            'feeGroup' => $this->whenLoaded('feeGroup', [
                'id' => $this->feeGroup?->id,
                'group' => $this->feeGroup?->group,
                'amount' => $this->feeGroup?->amount,
            ]),
            'academicYear' => $this->whenLoaded('academicYear', [
                'id' => $this->academicYear?->id,
                'year' => $this->academicYear?->year,
                'semester' => $this->academicYear?->semester,
                'is_active' => $this->academicYear?->is_active,
            ]),
        ];
    }
}

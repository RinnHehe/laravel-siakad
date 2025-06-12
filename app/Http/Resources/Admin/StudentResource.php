<?php

namespace App\Http\Resources\Admin;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'student_number' => $this->student_number,
            'semester' => $this->semester,
            'batch' => $this->batch,
            'created_at' => $this->created_at,
            'user' => $this->whenLoaded('user', [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
                'avatar' => $this->user?->avatar ? Storage::url($this->user?->avatar) : null,
            ]),
            'faculty' => $this->whenLoaded('faculty', [
                'id' => $this->faculty?->id,
                'name' => $this->faculty?->name,
            ]),
            'department' => $this->whenLoaded('department', [
                'id' => $this->department?->id,
                'name' => $this->department?->name,
            ]),
            'classroom' => $this->whenLoaded('classroom', [
                'id' => $this->classroom?->id,
                'name' => $this->classroom?->name,
            ]),
            'feeGroup' => $this->whenLoaded('feeGroup', [
                'id' => $this->feeGroup?->id,
                'group' => $this->feeGroup?->group,
                'amount' => $this->feeGroup?->amount,
            ]),
        ];
    }
}

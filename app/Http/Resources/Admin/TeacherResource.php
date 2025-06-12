<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TeacherResource extends JsonResource
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
            'teacher_number' => $this->teacher_number,
            'academic_title' => $this->academic_title,
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
        ];
    }
}

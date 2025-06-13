<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'credit' => $this->credit,
            'semester' => $this->semester,
            'created_at' => $this->created_at,
            'faculty' => $this->whenLoaded('faculty',[
                'id' => $this->faculty?->id,
                'name' => $this->faculty?->name,
            ]),
            'department' => $this->whenLoaded('department',[
                'id' => $this->department?->id,
                'name' => $this->department?->name,
            ]),
            'teacher' => $this->whenLoaded('teacher',[
                'id' => $this->teacher?->id,
                'name' => $this->teacher?->user?->name,
            ]),
            'academicYear' => $this->whenLoaded('academicYear',[
                'id' => $this->academicYear?->id,
                'name' => $this->academicYear?->name,
            ]),
        ];
    }
}

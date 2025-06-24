<?php

namespace App\Http\Resources\Operator;

use App\Http\Resources\Admin\ScheduleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StudyPlanOperatorResource extends JsonResource
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
            'status' => $this->status,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'semester' => $this->semester,
            'student' => $this->whenLoaded('student', [
                'id' => $this->student?->id,
                'student_number' => $this->student?->student_number,
                'name' => $this->student?->user?->name,
                'avatar' => $this->student?->user?->avatar ? Storage::url($this->student?->user?->avatar) : null,
                'classroom' => $this->student?->classroom?->name
            ]),
            'academicYear' => $this->whenLoaded('academicYear', [
                'id' => $this->academicYear?->id,
                'name' => $this->academicYear?->name,
            ]),
            'schedules' => $this->whenLoaded('schedules', ScheduleResource::collection($this->schedules)),
        ];
    }
}

<?php

namespace App\Http\Resources\Student;

use App\Http\Resources\Admin\ScheduleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyPlanScheduleResource extends JsonResource
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
          'academicYear' => $this->whenLoaded('academicYear', [
            'id' => $this->academicYear->id,
            'name' => $this->academicYear->name,
          ]),
          'schedules' => $this->whenLoaded('schedules', ScheduleResource::collection($this->schedules)),
        ];
    }
}

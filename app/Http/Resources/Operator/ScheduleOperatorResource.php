<?php

namespace App\Http\Resources\Operator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ScheduleOperatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array{
        return [
            'id' => $this->id,
            'start_time' => Carbon::parse($this->start_time)->format('H:i'),
            'end_time' => Carbon::parse($this->end_time)->format('H:i'),
            'day_of_week' => $this->day_of_week,
            'quota' => $this->quota,
            'created_at' => $this->created_at,
            'taken_quota' => $this->taken_quota,
            'course' => $this->whenLoaded('course', [
                'id' => $this->course->id,
                'name' => $this->course->name,
                'code' => $this->course->code,
                'credit' => $this->course->credit,
                'teacher' => $this->course->teacher?->user?->name,
            ]),
            'classroom' => $this->whenLoaded('classroom', [
                'id' => $this->classroom->id,
                'name' => $this->classroom->name,
                'slug' => $this->classroom->slug,
            ]),
            'academic_year' => $this->whenLoaded('academicYear', [
                'id' => $this->academicYear->id,
                'name' => $this->academicYear->name,
            ]),
        ];
    }
}

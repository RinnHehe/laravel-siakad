<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyPlanStudentResource extends JsonResource
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
            'schedules' => $this->whenLoaded('schedules', function() {
                return $this->schedules->map(function($schedule) {
                    return [
                        'id' => $schedule->id,
                        'day_of_week' => $schedule->day_of_week,
                        'start_time' => $schedule->start_time,
                        'end_time' => $schedule->end_time,
                        'course' => [
                            'name' => $schedule->course->name,
                            'credit' => $schedule->course->credit,
                        ],
                        'classroom' => [
                            'name' => $schedule->classroom->name,
                        ],
                        'academicYear' => [
                            'name' => $schedule->academicYear->name,
                        ],
                    ];
                });
            }, []),
        ];
    }
}

<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CourseStudentClassroomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attendances_count = $this->attendances_count ?? 0;
        $tasks_count = $this->tasks_count ?? 0;
        $uts_count = $this->uts_count ?? 0;
        $uas_count = $this->uas_count ?? 0;

        return [
            'id' => $this->id,
            'student_number' => $this->student_number,
            'user' => $this->whenLoaded('user', [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
                'avatar' => $this->user?->avatar ? Storage::url($this->user?->avatar) : null,
            ]),
            'attendances' => $this->whenLoaded('attendances', CourseAttendanceResource::collection($this->attendances)),
            'grades' => $this->whenLoaded('grades', CourseGradeResource::collection($this->grades)),
            'total' => [
                'attendances_count' => $attendances_count,
                'tasks_count' => $tasks_count,
                'uts_count' => $uts_count,
                'uas_count' => $uas_count,
            ],
            'percentage' => [
                'attendance_percentage' => $attendance_percentage = round(($attendances_count / 12) * 10, 2),
                'task_percentage' => $task_percentage = round(($tasks_count / 10) * 0.2, 2),
                'uts_percentage' => $uts_percentage = round($uts_count * 0.3, 2),
                'uas_percentage' => $uas_percentage = round($uas_count * 0.4, 2),
            ],
            'final_score' => $final_score = round($attendance_percentage + $task_percentage + $uts_percentage + $uas_percentage, 2),
            'letter' => getLetterGrade($final_score),
        ];
    }
}

<?php

namespace App\Traits;

use App\Models\Attendance;
use App\Models\Grade;

trait CalculatesFinalScore
{
    public function getAttendanceCount(int $studentId, int $courseId, int $classroomId): int
    {
        return Attendance::query()
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('classroom_id', $classroomId)
            ->whereBetween('section', [1, 12])
            ->where('status', true)
            ->count();
    }

    public function getGradeCount(int $studentId, int $courseId, int $classroomId, string $category): float
    {
        $grade = Grade::where([
            'student_id' => $studentId,
            'course_id' => $courseId,
            'classroom_id' => $classroomId,
            'category' => $category,
        ])->first();

        return $grade ? $grade->grade : 0;
    }

    public function calculateAttendancePercentage(int $attendanceCount, int $totalSessions = 12): float
    {
        return round(($attendanceCount / $totalSessions) * 10, 2);
    }

    public function calculateTaskPercentage(float $score): float
    {
        return $score / 10;
    }

    public function calculateUTSPercentage(float $score): float
    {
        return $score;
    }

    public function calculateUASPercentage(float $score): float
    {
        return $score;
    }

    public function calculateFinalScore(float $attendancePercentage, float $taskPercentage, float $utsPercentage, float $uasPercentage): float
    {
        return ($attendancePercentage * 0.1) + ($taskPercentage * 0.2) + ($utsPercentage * 0.3) + ($uasPercentage * 0.4);
    }

    public function getWeight(string $letter): float
    {
        return match ($letter) {
            'A' => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B' => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C' => 2.0,
            'D' => 1.0,
            default => 0.0,
        };
    }
}

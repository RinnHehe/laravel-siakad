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

    public function getGradeCount(int $studentId, int $courseId, int $classroomId, string $category): int
    {
        $grade = Grade::query()
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('classroom_id', $classroomId)
            ->where('category', $category);

        if ($category === 'tugas') {
            $grade->whereBetween('section', [1, 10]);
        } elseif (in_array($category, ['uts', 'uas'])) {
            $grade->whereNull('section');
        }

        return $grade->sum('grade');
    }

    public function calculateAttendancePercentage(int $attendanceCount, int $totalSessions = 12): float
    {
        return round(($attendanceCount / $totalSessions) * 10, 2);
    }

    public function calculateTaskPercentage(int $taskCount, int $totalTasks = 10): float
    {
        return round(($taskCount / $totalTasks) * 0.2, 2);
    }

    public function calculateUTSPercentage(int $utsCount): float
    {
        return round($utsCount * 0.3, 2);
    }

    public function calculateUASPercentage(int $uasCount): float
    {
        return round($uasCount * 0.4, 2);
    }

    public function calculateFinalScore (
        float $attendancePercentage,
        float $taskPercentage,
        float $utsPercentage,
        float $uasPercentage
    ): float {
        return round($attendancePercentage + $taskPercentage + $utsPercentage + $uasPercentage, 2);
    }

    public function getWeight(string $letterGrade): float
    {
        $gradePoints = [
            'A' => 4.00,
            'A-' => 3.70,
            'B+' => 3.30,
            'B' => 3.00,
            'B-' => 2.70,
            'C+' => 2.30,
            'C' => 2.00,
            'C-' => 1.70,
            'D' => 1.00,
            'E' => 0.00,
        ];

        return $gradePoints[$letterGrade] ?? 0.00;
    }
}

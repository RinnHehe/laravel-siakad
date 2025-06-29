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
        if ($category === 'tugas') {
            $totalGrade = 0;
            $count = 0;

            // Log for debugging
            \Illuminate\Support\Facades\Log::info('Calculating task grades:', [
                'student_id' => $studentId,
                'course_id' => $courseId,
                'classroom_id' => $classroomId
            ]);

            for ($i = 1; $i <= 10; $i++) {
                $grade = Grade::where([
                    'student_id' => $studentId,
                    'course_id' => $courseId,
                    'classroom_id' => $classroomId,
                    'category' => $category,
                    'section' => $i,
                ])->value('grade');

                \Illuminate\Support\Facades\Log::info("Task $i grade:", ['grade' => $grade]);

                if ($grade !== null) {
                    $totalGrade += $grade;
                    $count++;
                }
            }

            $average = $count > 0 ? ($totalGrade / $count) : 0;
            \Illuminate\Support\Facades\Log::info('Task calculation result:', [
                'total_grade' => $totalGrade,
                'count' => $count,
                'average' => $average
            ]);

            return $average;
        }

        $grade = Grade::where([
            'student_id' => $studentId,
            'course_id' => $courseId,
            'classroom_id' => $classroomId,
            'category' => $category,
        ])->whereNull('section')->value('grade') ?? 0;

        \Illuminate\Support\Facades\Log::info("$category grade:", ['grade' => $grade]);

        return $grade;
    }

    public function calculateAttendancePercentage(int $attendanceCount, int $totalSessions = 12): float
    {
        return ($attendanceCount / $totalSessions) * 100;
    }

    public function calculateTaskPercentage(float $score): float
    {
        return $score;
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
        // Log nilai-nilai untuk debugging
        \Illuminate\Support\Facades\Log::info('Nilai Komponen:', [
            'attendance' => $attendancePercentage,
            'task' => $taskPercentage,
            'uts' => $utsPercentage,
            'uas' => $uasPercentage
        ]);

        $final = round(
            ($attendancePercentage * 0.1) +
            ($taskPercentage * 0.2) +
            ($utsPercentage * 0.3) +
            ($uasPercentage * 0.4),
            2
        );

        // Log nilai akhir
        \Illuminate\Support\Facades\Log::info('Nilai Akhir:', ['final' => $final]);

        return $final;
    }

    public function getWeight(string $letter): float
    {
        return match ($letter) {
            'A+' => 4.0,
            'A' => 3.7,
            'A-' => 3.3,
            'B+' => 3.0,
            'B' => 2.7,
            'B-' => 2.3,
            'C+' => 2.0,
            'C' => 1.7,
            'C-' => 1.3,
            'D' => 1.0,
            'E' => 0.0,
            default => 0.0,
        };
    }
}

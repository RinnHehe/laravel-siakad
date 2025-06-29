<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\CourseClassroomRequest;
use App\Http\Resources\Teacher\CourseStudentClassroomResource;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudyResult;
use App\Models\StudyResultGrade;
use App\Traits\CalculatesFinalScore;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Response;
use Inertia\Inertia;
use Throwable;

class CourseClassroomController extends Controller
{
    use CalculatesFinalScore;

    public function getAttendanceCount($studentId, $courseId, $classroomId)
    {
        return Attendance::where([
            'student_id' => $studentId,
            'course_id' => $courseId,
            'classroom_id' => $classroomId,
        ])
        ->where('status', true)
        ->count();
    }

    public function calculateAttendancePercentage($attendanceCount)
    {
        $maxAttendance = 12; // Total pertemuan
        return ($attendanceCount / $maxAttendance) * 100;
    }

    public function index(Course $course, Classroom $classroom): Response
    {
        $schedule = Schedule::query()
            ->where('course_id', $course->id)
            ->where('classroom_id', $classroom->id)
            ->first();

        $students = Student::query()
            ->where('faculty_id', $course->faculty_id)
            ->where('department_id', $course->department_id)
            ->where('classroom_id', $classroom->id)
            ->filter(request()->only(['search']))
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', fn($query) => $query->where('name', 'student'));
            })
            ->whereHas('studyPlans', function ($query) use ($schedule) {
                $query->where('academic_year_id', activeAcademicYear()->id)
                    ->approved()
                    ->whereHas('schedules', fn($query) => $query->where('schedule_id', $schedule->id));
            })
            ->with([
                'user',
                'attendances' => fn($query) => $query
                    ->where('course_id', $course->id)
                    ->where('classroom_id', $classroom->id)
                    ->where('status', true),
                'grades' => fn($query) => $query
                    ->where('course_id', $course->id)
                    ->where('classroom_id', $classroom->id)
            ])
            ->withCount([
                'attendances as attendances_count' => fn($query) => $query
                    ->where('course_id', $course->id)
                    ->where('classroom_id', $classroom->id)
                    ->where('status', true),
            ])
            ->withSum(
                ['grades as tasks_count' => fn($query) => $query
                    ->where('course_id', $course->id)
                    ->where('classroom_id', $classroom->id)
                    ->where('category', 'tugas')
                    ->whereBetween('section', [1, 10])],
                'grade',
            )
            ->withSum(
                ['grades as uts_count' => fn($query) => $query
                    ->where('course_id', $course->id)
                    ->where('classroom_id', $classroom->id)
                    ->where('category', 'uts')
                    ->whereNull('section')],
                'grade',
            )
            ->withSum(
                ['grades as uas_count' => fn($query) => $query
                    ->where('course_id', $course->id)
                    ->where('classroom_id', $classroom->id)
                    ->where('category', 'uas')
                    ->whereNull('section')],
                'grade',
            )
            ->get();

        return Inertia::render('Teachers/Classrooms/Index', [
            'page_settings' => [
                'title' => "Kelas {$classroom->name} - Mata Kuliah {$course->name}",
                'subtitle' => 'Menampilkan data mahasiswa',
                'method' => 'PUT',
                'action' => route('teachers.classrooms.sync', [$course, $classroom]),
            ],
            'course' => $course,
            'classroom' => $classroom,
            'students' => CourseStudentClassroomResource::collection($students),
            'state' => [
                'search' => request()->search ?? '',
            ],
        ]);
    }

    public function calculateGPA(int $studentId)
    {
        $student = Student::query()
            ->where('id', $studentId)
            ->first();

        $studyResult = StudyResult::query()
            ->where('student_id', $student->id)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->where('semester', $student->semester)
            ->first();

        if (!$studyResult){
            return 0;
        }

        $studyResultGrades = StudyResultGrade::query()
            ->where('study_result_id', $studyResult->id)
            ->get();

        $totalScore = 0;
        $totalWeight = 0;

        foreach ($studyResultGrades as $grade) {
            $finalScore = min($grade->grade, 100);
            $gpaScore = ($finalScore / 100) * 4;
            $weight = $grade->weight_of_value;

            $totalScore += $gpaScore * $weight;
            $totalWeight += $weight;
        }

        if ($totalWeight > 0) {
            return min(round($totalScore / $totalWeight, 2), 4);
        }

        return 0;
    }

    public function updateGPA(int $studentId)
    {
        $student = Student::query()
            ->where('id', $studentId)
            ->first();

        $gpa = $this->calculateGPA($student->id);
        $studyResult = StudyResult::query()
            ->where('student_id', $student->id)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->where('semester', $student->semester)
            ->first();

        if ($studyResult) {
            $studyResult->update([
                'gpa' => $gpa,
            ]);
        }
    }

    public function sync(Course $course, Classroom $classroom, CourseClassroomRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            // Debug incoming data
            Log::info('Request data:', $validated);

            $attendances = $validated['attendances'] ?? [];

            // Update attendance records only for submitted sections
            if (!empty($attendances)) {
                foreach ($attendances as $attendance) {
                    // Delete only the specific attendance record if exists
                    Attendance::where([
                        'student_id' => $attendance['student_id'],
                        'course_id' => $course->id,
                        'classroom_id' => $classroom->id,
                        'section' => $attendance['section'],
                    ])->delete();

                    // Create new attendance record
                    Attendance::create([
                        'student_id' => $attendance['student_id'],
                        'course_id' => $course->id,
                        'classroom_id' => $classroom->id,
                        'section' => $attendance['section'],
                        'status' => $attendance['status'],
                    ]);
                }
            }

            $grades = $validated['grades'] ?? [];

            // Clear and insert new grade records
            if (!empty($grades)) {
                foreach ($grades as $grade) {
                    Grade::updateOrCreate(
                        [
                            'student_id' => $grade['student_id'],
                            'course_id' => $course->id,
                            'classroom_id' => $classroom->id,
                            'category' => $grade['category'],
                            'section' => $grade['section'],
                        ],
                        [
                            'grade' => $grade['grade'],
                        ]
                    );
                }
            }

            $studentIds = collect($attendances)
                ->pluck('student_id')
                ->merge(collect($grades)->pluck('student_id'))
                ->unique()
                ->values();

            Log::info('Processing student IDs:', ['ids' => $studentIds]);

            $studyResult = StudyResult::query()
                ->whereIn('student_id', $studentIds)
                ->get();

            $studyResult->each(function($result) use($course, $classroom){
                $final_score = $this->calculateFinalScore(
                    attendancePercentage: $this->calculateAttendancePercentage(
                        $this->getAttendanceCount($result->student_id, $course->id, $classroom->id)
                    ),
                    taskPercentage: (
                        $this->calculateTaskPercentage(
                            $this->getGradeCount($result->student_id, $course->id, $classroom->id, 'tugas')
                        )
                    ),
                    utsPercentage: (
                        $this->calculateTaskPercentage(
                            $this->getGradeCount($result->student_id, $course->id, $classroom->id, 'uts')
                        )
                    ),
                    uasPercentage: (
                        $this->calculateTaskPercentage(
                            $this->getGradeCount($result->student_id, $course->id, $classroom->id, 'uas')
                        )
                    ),
                );

                $grades = StudyResultGrade::updateOrCreate([
                    'study_result_id' => $result->id,
                    'course_id' => $course->id,
                ], [
                    'grade' => $final_score,
                    'letter' => getLetterGrade($final_score),
                    'weight_of_value' => $this->getWeight(getLetterGrade($final_score)),
                ]);

                $this->updateGPA($result->student_id);
            });

            DB::commit();
            flashMessage('Berhasil melakukan perubahan');
            return to_route('teachers.classrooms.index', [$course, $classroom]);

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error in sync method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('teachers.classrooms.index', [$course, $classroom]);
        }
    }
}

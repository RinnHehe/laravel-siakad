<?php

namespace App\Models;

use App\Enums\ScheduleDay;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    protected $fillable = [
        'faculty_id',
        'department_id',
        'course_id',
        'classroom_id',
        'academic_year_id',
        'start_time',
        'end_time',
        'day_of_week',
        'quote',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => ScheduleDay::class,
        ];
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function studyPlans(): BelongsToMany
    {
        return $this->belongsToMany(StudyPlan::class, 'study_plan_schedule')->withTimestamps();
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereAny([
                'start_time',
                'end_time',
                'day_of_week',
            ], 'REGEXP', $search)
                ->WhereHas('faculty', fn($query) => $query->where('name', 'REGEXP', $search))
                ->WhereHas('department', fn($query) => $query->where('name', 'REGEXP', $search))
                ->WhereHas('course', fn($query) => $query->where('name', 'REGEXP', $search))
                ->WhereHas('classroom', fn($query) => $query->where('name', 'REGEXP', $search));
        });
    }

    public function scopeSorting(Builder $query, array $sorts): void
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
            match($sorts['field']) {
                'faculty_id' => $query->join('faculties', 'schedules.faculty_id', '=', 'faculties.id')
                    ->orderBy('faculties.name', $sorts['direction']),
                'department_id' => $query->join('departments', 'schedules.department_id', '=', 'departments.id')
                    ->orderBy('departments.name', $sorts['direction']),
                'course_id' => $query->join('courses', 'schedules.course_id', '=', 'courses.id')
                    ->orderBy('courses.name', $sorts['direction']),
                'classroom_id' => $query->join('classrooms', 'schedules.classroom_id', '=', 'classrooms.id')
                    ->orderBy('classrooms.name', $sorts['direction']),
            };
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StudyPlan extends Model
{
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'status',
        'notes',
    ];
    
    protected function casts(): array
    {
        return [
            'status' => StudyPlanStatus::class,
        ];
    }    

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class, 'study_plan_schedule')->withTimestamps();
    }
}

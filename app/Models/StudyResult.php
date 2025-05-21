<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyResult extends Model
{
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'semester',
        'gpa',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(StudyResultGrade::class);
    }
}

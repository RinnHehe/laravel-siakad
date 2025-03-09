<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyResultGrade extends Model
{
    protected $fillable = [
        'study_result_id',
        'course_id',
        'letter',
        'weight_of_value',
        'grade'
    ];

    public function studyResult(): BelongsTo
    {
        return $this->belongsTo(StudyResult::class);
    }
    
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}

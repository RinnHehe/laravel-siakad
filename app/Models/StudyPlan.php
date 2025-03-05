<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}

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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyResult extends Model
{
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'semester',
        'gpa',
    ];
}

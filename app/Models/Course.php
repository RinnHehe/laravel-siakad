<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'faculty_id',
        'department_id',
        'teacher_id',
        'academic_year_id',
        'code',
        'name',
        'credit',
        'semester',
    ];    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'course_id',
        'student_id',
        'classroom_id',
        'grade',
        'section',
        'category'
    ];
}

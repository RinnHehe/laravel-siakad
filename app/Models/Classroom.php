<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = [
        'faculty_id',
        'department_id',
        'academic_year_id',
        'name',
        'slug',
    ];
}

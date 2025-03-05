<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = [
        'fee_code',
        'student_id',
        'fee_group_id',
        'academic_year_id',
        'semester',
        'status',
    ];
}

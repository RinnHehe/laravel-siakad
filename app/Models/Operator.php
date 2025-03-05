<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $fillable = [
        'user_id',
        'faculty_id',
        'department_id',
        'employee_number',
    ];    
}

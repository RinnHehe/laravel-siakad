<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Operator extends Model
{
    protected $fillable = [
        'user_id',
        'faculty_id',
        'department_id',
        'employee_number',
    ];    

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search){
            $query->where('employee_number', 'RAGEXP', $search)
            ->orWhereHas('user', fn($query) => $query->whereAny(['name', 'email'], 'RAGEXP', $search))
            ->orWhereHas('faculty', fn($query) => $query->where('name', 'RAGEXP', $search))
            ->orWhereHas('department', fn($query) => $query->where('name', 'RAGEXP', $search));
        });
    }


    public function scopeSorting(Builder $query, array $sorts): void
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function($query) use($sorts){
            match ($sorts['field']){
                'faculty_id' => $query->join('faculties', 'operators.faculty_id', '=', 'faculties.id')
                    ->orderBy('faculties.name', $sorts['direction']),
                'department_id' => $query->join('departments', 'operators.department_id', '=', 'departments.id')
                    ->orderBy('departments.name', $sorts['direction']),
                'name' => $query->join('users', 'operators.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sorts['direction']),
                'email' => $query->join('users', 'operators.user_id', '=', 'users.id')
                    ->orderBy('users.email', $sorts['direction']),

                default =>$query->orderBy($sorts['field'], $sorts['direction']),
            };
        });
    }
}

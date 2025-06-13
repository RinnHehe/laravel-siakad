<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function feeGroup(): BelongsTo
    {
        return $this->belongsTo(FeeGroup::class);
    }
    
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function($query, $search) {
            $query->where('status', 'RAGEXP', $search)
            ->orWhereHas('academicYear', fn($query) => $query->where ('name', 'RAGEXP', $search))
            ->orWhereHas('student.user', fn($query) => $query->whereAny([
                'name',
                'email',
            ], 'RAGEXP', $search));
        });
    }

    public function scopeSorting(Builder $query, array $sorts): void
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function($query) use ($sorts){
            match ($sorts['field']) {
                'academic_year_id' => $query->join('academic_years', 'fees.academic_year_ud', '=', 'academic_years_id')
                    ->orderBy('academic_years.name', $sorts['direction']),
                'name' => $query
                    ->leftJoin('student', 'student_id', '=', 'fees.student_id')
                    ->leftJoin('users', 'students.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sorts['direction']),
                default => $query->orderBy($sorts['field'], $sorts['direction']),
            };
        });
    }
}

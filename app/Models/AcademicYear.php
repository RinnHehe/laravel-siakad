<?php

namespace App\Models;

use App\Enums\AcademicYearSemester;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use Sluggable;
    protected $fillable = [
        'name',
        'slug',
        'start_date',
        'end_date',
        'semester',
        'is_active',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected function casts(): array
    {
        return [
            'semester' => AcademicYearSemester::class,
        ];
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereAny([
                'name',
                'semester',
            ], 'REGEXP', $search);
        });
    }

    public function scopeSorting(Builder $query, array $sorts): void
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
            $query->orderBy($sorts['field'], $sorts['direction']);
        });
    }
}

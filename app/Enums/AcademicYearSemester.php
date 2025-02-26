<?php

namespace App\Enums;

enum AcademicYearSemester: string
{
    case ODD = 'Ganjil';
    case EVEN = 'Genap';

    public static function options(): array
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}

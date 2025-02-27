<?php

namespace App\Enums;

enum StudyPlanStatus: string
{
    case PENDING = 'Pending';
    case APPROVED = 'Approve';
    case REJECT = 'Reject';

    public static function options(): array
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}

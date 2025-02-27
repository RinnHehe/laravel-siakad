<?php

namespace App\Enums;

enum ScheduleDay: string
{
    case SENIN = 'Senin';
    case SELASA = 'Selasa';
    case RABU = 'Rabu';
    case KAMIS = 'Kamis';
    case JUMAT = 'Jumat';
    case SABTU = 'Sabtu';
    case MINGGU = 'Minggu';

    public static function options(): array
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}

<?php

use App\Models\AcademicYear;

if (!function_exists('flashMessage')) {
    function flashMessage($message, $type = 'success'): void
    {
        session()->flash('message', $message);
        session()->flash('type', $type);
    }
}

if (!function_exists('signatureMidtrans')) {
    function signatureMidtrans($order_id, $status_code, $gross_amount, $server_key): string
    {
        return hash('sha512', $order_id . $status_code . $gross_amount . $server_key);
    }
}

if (!function_exists('activeAcademicYear')) {
    function activeAcademicYear()
    {
        return AcademicYear::query()->where('is_active', true)->first();
    }
}

if (!function_exists('getLetterGrade')) {
    function getLetterGrade($grade): string
    {
        return match (true) {
            $grade >= 90 => 'A',
            $grade >= 85 => 'A-',
            $grade >= 80 => 'B+',
            $grade >= 75 => 'B',
            $grade >= 70 => 'B-',
            $grade >= 65 => 'C+',
            $grade >= 60 => 'C',
            $grade >= 55 => 'C-',
            $grade >= 50 => 'D',
            default => 'E', 
        };
    }
}

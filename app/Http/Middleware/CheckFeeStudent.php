<?php

namespace App\Http\Middleware;

use App\Enums\FeeStatus;
use App\Models\Fee;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFeeStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $fee = Fee::query()
            ->where('student_id', Auth::user()->student->id)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->where('semester', Auth::user()->student->semester)
            ->where('status', FeeStatus::SUCCESS->value)
            ->exists();

        if (!$fee) {
            flashMessage('Silahkan melakukan pembayaran UKT terlebih dahulu', 'warning');
            return to_route('students.fees.index');
        }
        return $next($request);
    }
}

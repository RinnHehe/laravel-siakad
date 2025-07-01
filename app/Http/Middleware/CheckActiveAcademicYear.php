<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveAcademicYear
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!activeAcademicYear()) {
            if (Auth::user()->hasRole('Admin')) {
                flashMessage('Tidak ada tahun ajaran yang aktif, Silahkan tambahkan terlebih dahulu', 'warning');
                return to_route('admin.academic-years.index');
            } elseif
             (Auth::user()->hasRole('Operator')) {
                flashMessage('Tidak ada tahun ajaran yang aktif, Harap hubungi admin', 'warning');
                return to_route('operators.dashboard');
            }
        }
        return $next($request);
    }
}

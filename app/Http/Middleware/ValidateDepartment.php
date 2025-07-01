<?php

namespace App\Http\Middleware;

use App\Models\Department;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateDepartment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $department = Department::query()
            ->where('id', $request->department_id)
            ->where('faculty_id', $request->faculty_id)
            ->exists();

        if (! $department) {
            flashMessage('Program studi yang ada pilih tidak terdaftar pada jurusan yang anda pilih', 'error');
            return back();
        }

        return $next($request);
    }
}

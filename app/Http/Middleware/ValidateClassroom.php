<?php

namespace App\Http\Middleware;

use App\Models\Classroom;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateClassroom
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $classroom = Classroom::query()
            ->where('id', $request->classroom_id)
            ->where('faculty_id', $request->faculty_id)
            ->where('department_id', $request->department_id)
            ->exists();

        if (!$classroom) {
            flashMessage('Kelas tersebut tidak ada di program studi atau fakultas yang anda pilih', 'warning');
            return to_route('admin.schedules.index');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();

            session()->regenerate();

            if (Auth::user()->hasRole('Admin')) {
                return Inertia::location(route('admin.dashboard', absolute: false));
            } else if (Auth::user()->hasRole('Teacher')) {
                return Inertia::location(route('teachers.dashboard', absolute: false));
            } else if (Auth::user()->hasRole('Operator')) {
                return Inertia::location(route('operators.dashboard', absolute: false));
            } else if (Auth::user()->hasRole('Student')) {
                return Inertia::location(route('students.dashboard', absolute: false));
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Inertia::render('Auth/Login', [
                'errors' => [
                    'email' => 'Email atau password yang anda masukkan salah.'
                ],
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::location('/');
    }
}
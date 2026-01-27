<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $userRole = $user->role ?? 'user';

        // STRICT: Hanya user dengan role='user' yang boleh akses route user
        // Admin TIDAK BOLEH akses route user (harus logout dulu atau pakai browser berbeda)
        if ($userRole !== 'user') {
            // Jika admin mencoba akses route user, block dengan 403
            // Admin harus akses via /admin/*, bukan /user/*
            abort(403, 'Akses ditolak. Admin harus mengakses dashboard admin di /admin/dashboard');
        }

        return $next($request);
    }
}


<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Ensure user can only access routes based on their role
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Expected role (admin or user)
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check authentication - STRICT: tidak ada fallback
        if (!Auth::check()) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'Sesi tidak valid. Silakan login ulang.');
        }

        // Check role - STRICT: tidak ada fallback, tidak ada auto-redirect
        // SELALU baca dari database, jangan dari session
        $userRole = $user->role ?? 'user';
        
        if ($role === 'admin') {
            // Admin access - HANYA role 'admin' yang boleh
            if ($userRole !== 'admin') {
                abort(403, 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
            }
        } elseif ($role === 'user') {
            // User access - HANYA role 'user' yang boleh
            if ($userRole !== 'user') {
                abort(403, 'Akses ditolak. Hanya user yang dapat mengakses halaman ini.');
            }
        }

        return $next($request);
    }
}


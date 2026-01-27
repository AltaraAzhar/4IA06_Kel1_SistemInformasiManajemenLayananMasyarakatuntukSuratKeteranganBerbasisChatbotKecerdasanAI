<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check authenticated user via guard web
        if (!Auth::guard('web')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        // STRICT: User harus memiliki role admin
        // SELALU baca dari database, tidak pakai session khusus
        $user = Auth::guard('web')->user();
        if (!$user || $user->role !== 'admin') {
            // Jika bukan admin, logout dan redirect
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('admin.login')
                ->with('error', 'Akses tidak diizinkan. Hanya admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}


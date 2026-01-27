<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateOrAdmin
{
    /**
     * Handle an incoming request.
     * Allow access if user is authenticated OR admin session exists
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin session exists (via .env login)
        if (session('admin_authenticated')) {
            return $next($request);
        }

        // Check if admin is authenticated via guard
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // Check if user is authenticated via guard web
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            // Jika user sudah login dengan role admin, set session admin
            if ($user && $user->role === 'admin') {
                session(['admin_authenticated' => true, 'admin_email' => $user->email]);
                return $next($request);
            }
        }

        // Redirect to appropriate login based on route
        if ($request->is('admin/*')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu.');
    }
}


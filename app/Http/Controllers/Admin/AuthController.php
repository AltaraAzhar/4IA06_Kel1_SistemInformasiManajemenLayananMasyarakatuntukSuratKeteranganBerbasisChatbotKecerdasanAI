<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form (shared with user)
     */
    public function showLoginForm()
    {
        return view('admin.login_admin');
    }

    /**
     * Handle admin login request
     * STRICT: Hanya user dengan role='admin' yang boleh login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // ===== ADMIN LOGIN VIA .ENV =====
        // SECURITY: Gunakan config() bukan env() langsung
        $adminEmail = config('services.admin.email');
        $adminPassword = config('services.admin.password');
        
        // SECURITY FIX: Compare password dengan hash yang sudah ada di database
        // Jika user belum ada, kita akan create dengan hashed password
        if ($adminEmail && $adminPassword && 
            $request->email === $adminEmail) {
            
            // Cari atau buat user admin di database
            $user = User::where('email', $adminEmail)->first();
            $passwordMatch = false;
            
            if (!$user) {
                // SECURITY: Create admin with hashed password
                // Only create if password matches .env
                if ($request->password === $adminPassword) {
                    $user = User::create([
                        'name' => 'Administrator',
                        'email' => $adminEmail,
                        'password' => Hash::make($adminPassword),
                        'role' => 'admin',
                        'nik_or_nip' => 'ADMIN001',
                        'phone' => '-',
                        'address' => 'Kelurahan Pabuaran Mekar',
                    ]);
                    $passwordMatch = true;
                }
            } else {
                // SECURITY: Check hashed password from database
                if (Hash::check($request->password, $user->password)) {
                    $passwordMatch = true;
                } elseif ($request->password === $adminPassword && !Hash::check($request->password, $user->password)) {
                    // Migration: If .env password matches but DB has different hash, update it
                    $user->update(['password' => Hash::make($adminPassword)]);
                    $passwordMatch = true;
                }
                
                // Update role jika belum admin
                if ($user->role !== 'admin') {
                    $user->update(['role' => 'admin']);
                }
            }
            
            // Only proceed if password matches
            if (!$passwordMatch) {
                return back()->withErrors([
                    'email' => 'Email atau password salah.',
                ])->withInput($request->only('email'));
            }
            
            // Login menggunakan guard web
            Auth::login($user, $request->filled('remember'));
            
            // Regenerate session untuk security
            $request->session()->regenerate();

            // STRICT VALIDATION: Cek role setelah login
            if ($user->role !== 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->withErrors([
                    'email' => 'Akun ini bukan akun admin.',
                ])->withInput($request->only('email'));
            }

            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang Admin!');
        }

        // ===== ADMIN LOGIN VIA DATABASE =====
        // Cek apakah user ada dan role = admin
        $user = User::where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Login menggunakan guard web
            Auth::login($user, $request->filled('remember'));
            
            // Regenerate session untuk security
            $request->session()->regenerate();

            // STRICT VALIDATION: Cek role setelah login
            if ($user->role !== 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->withErrors([
                    'email' => 'Akun ini bukan akun admin.',
                ])->withInput($request->only('email'));
            }

            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang Admin!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle admin logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login')->with('success', 'Anda telah berhasil logout.');
    }
}


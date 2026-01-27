<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Show login form (shared with admin)
     */
    public function showLoginForm()
    {
        return view('user.login_user');
    }

    /**
     * Handle user login request
     * STRICT: Hanya user dengan role='user' yang boleh login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');
    
        // Attempt login
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // STRICT VALIDATION: Cek role setelah login
            if ($user->role !== 'user') {
                // Logout jika role tidak sesuai
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->withErrors([
                    'email' => 'Akun ini bukan akun user. Silakan login sebagai admin.',
                ])->withInput($request->only('email'));
            }
            
            // Regenerate session untuk security
            $request->session()->regenerate();

            return redirect()->route('user.dashboard')
                ->with('success', 'Selamat datang, ' . $user->name . '!');
        }
    
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('user.register_user');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        // Prevent admin email from being used for registration
        if ($request->email === env('ADMIN_EMAIL')) {
            return back()->withErrors([
                'email' => 'Email ini tidak dapat digunakan untuk registrasi.',
            ])->withInput($request->only('name', 'email', 'nik_or_nip', 'phone', 'address'));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'nik_or_nip' => 'required|string|max:18',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'role' => ['required', Rule::in(['user'])],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.in' => 'Role admin tidak dapat didaftarkan. Silakan hubungi administrator.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nik_or_nip' => $validated['nik_or_nip'],
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? '',
            'role' => 'user', // Force role to 'user'
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('user.login')->with('success', 'Pendaftaran berhasil! Silakan login dengan akun Anda.');
    }

    /**
     * Handle user logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login')->with('success', 'Anda telah berhasil logout.');
    }
}


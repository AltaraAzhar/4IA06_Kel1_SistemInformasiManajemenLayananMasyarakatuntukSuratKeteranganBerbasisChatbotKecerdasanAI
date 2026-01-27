<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{
    /**
     * Display user profile
     */
    public function index()
    {
        $user = Auth::guard('web')->user();
        
        if (!$user) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        return view('user.profil_user', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::guard('web')->user();
        
        if (!$user) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Validasi
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nik_or_nip' => 'nullable|string|size:16|regex:/^[0-9]{16}$/',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'nik_or_nip.size' => 'NIK harus terdiri dari 16 digit angka.',
            'nik_or_nip.regex' => 'NIK hanya boleh berisi angka (0-9).',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // SECURITY: Update data dengan mass assignment protection
        // Hanya update field yang diizinkan
        $user->fill([
            'name' => $request->input('name'),
            'nik_or_nip' => $request->input('nik_or_nip'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ]);
        
        // SECURITY: Update password jika diisi (always hash)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        
        $user->save();

        return redirect()->route('user.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}


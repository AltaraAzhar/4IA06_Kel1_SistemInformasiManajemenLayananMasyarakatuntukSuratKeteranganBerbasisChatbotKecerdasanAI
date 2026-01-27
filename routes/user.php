<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\SuratController;
use App\Http\Controllers\PengajuanController as OldPengajuanController;
use App\Http\Controllers\ProfilController;

// ============================================
// USER ROUTES
// ============================================
// User Login & Register (public)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout - bisa diakses siapa saja yang sudah login (tanpa check role)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// User Protected Routes - STRICT: hanya role=user
Route::middleware(['auth', 'role:user'])->group(function () {
    // Dashboard User
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Pengajuan Surat (old controller for form)
    Route::get('/pengajuan', [OldPengajuanController::class, 'index'])->name('pengajuan');
    Route::get('/pengajuan/{jenis}', [OldPengajuanController::class, 'showForm'])->name('pengajuan.form');
    Route::post('/pengajuan/{jenis}', [OldPengajuanController::class, 'store'])->name('pengajuan.store');
    
    // Download Documents
    Route::get('/pengajuan/download/{document}', [OldPengajuanController::class, 'downloadDocument'])->name('pengajuan.download');
    
    // Surat (MongoDB-based routes) - WAJIB login
    Route::get('/surat/status', [SuratController::class, 'status'])->name('surat.status');
    Route::post('/surat', [SuratController::class, 'store'])->name('surat.store');
    Route::get('/surat', [SuratController::class, 'index'])->name('surat.index');
    Route::get('/surat/download/{id}', [SuratController::class, 'download'])->name('surat.download');
    
    // Profil User
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
});


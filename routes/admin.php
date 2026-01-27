<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SuratController;

// ============================================
// ADMIN ROUTES
// ============================================
// Admin Login (public) - shared login page
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Admin Logout - bisa diakses siapa saja yang sudah login (tanpa check role)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Protected Routes - STRICT: hanya role=admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Surat Admin (MongoDB-based routes)
    Route::get('/surat', [SuratController::class, 'index'])->name('surat.index');
    Route::get('/surat/{id}', [SuratController::class, 'show'])->name('surat.show');
    Route::post('/surat/{id}/process', [SuratController::class, 'process'])->name('surat.process');
    Route::get('/surat/{id}/documents', [SuratController::class, 'showDocuments'])->name('surat.documents');
    Route::get('/surat/{id}/documents/{index}/view', [SuratController::class, 'viewDocument'])->name('surat.documents.view');
    Route::get('/surat/{id}/history', [SuratController::class, 'showHistory'])->name('surat.history');
    Route::post('/surat/{id}/selesai', [SuratController::class, 'selesai'])->name('surat.selesai');
    Route::post('/surat/{id}/revise', [SuratController::class, 'revise'])->name('surat.revise');
    Route::get('/surat/{id}/detail-riwayat', [SuratController::class, 'detailRiwayat'])->name('surat.detail-riwayat');
});


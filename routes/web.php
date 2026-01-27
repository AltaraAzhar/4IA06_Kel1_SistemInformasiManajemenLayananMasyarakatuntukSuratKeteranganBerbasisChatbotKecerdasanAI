<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PreviewPengajuanController;
use App\Chatbot\Controllers\ChatbotController;

// ============================================
// PUBLIC ROUTES
// ============================================
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Alias untuk 'home' (sama dengan landing)
Route::get('/home', function () {
    return redirect()->route('landing');
})->name('home');

Route::get('/layanan', [LayananController::class, 'index'])->name('layanan');

Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

// Shared login page (redirects to user login)
Route::get('/login', function () {
    return redirect()->route('user.login');
})->name('login');

// Preview Pengajuan (Public, No Auth Required)
Route::get('/preview-pengajuan/{token}', [PreviewPengajuanController::class, 'show'])->name('preview.pengajuan');

// Chatbot API Routes (Public)
Route::prefix('api/chatbot')->name('chatbot.')->group(function () {
    Route::get('/status', [ChatbotController::class, 'status'])->name('status');
    Route::post('/message', [ChatbotController::class, 'message'])->name('message');
});


// ============================================
// NOTE: User and Admin routes are now in:
// - routes/user.php (for user routes)
// - routes/admin.php (for admin routes)
// ============================================

// ============================================
// TEST ROUTE REMOVED FOR SECURITY
// ============================================
// SECURITY: Test route removed - should not be in production

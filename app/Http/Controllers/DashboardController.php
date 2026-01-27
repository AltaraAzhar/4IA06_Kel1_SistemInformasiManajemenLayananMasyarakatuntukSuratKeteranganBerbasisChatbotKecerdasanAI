<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display user dashboard with statistics
     * HANYA untuk USER - tidak pernah redirect ke admin
     */
    public function index(Request $request)
    {
        // Get user from web guard
        $user = Auth::guard('web')->user();
        
        if (!$user) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // JANGAN redirect admin - biarkan middleware handle
        // User dashboard hanya untuk user biasa
        
        // Get statistics for current user (langsung dari MongoDB)
        // WAJIB filter berdasarkan user_id yang login - TIDAK PERNAH ambil data user lain
        $userId = $user->_id;
        
        if (!$userId) {
            return redirect()->route('user.login')->with('error', 'Sesi Anda tidak valid. Silakan login ulang.');
        }
        
        // Query langsung dari MongoDB tanpa cache
        // WAJIB: where('user_id', $userId) untuk keamanan
        $totalPengajuan = PengajuanSurat::where('user_id', $userId)->count();
        $menunggu = PengajuanSurat::where('user_id', $userId)->where('status', PengajuanSurat::STATUS_MENUNGGU)->count();
        $diproses = PengajuanSurat::where('user_id', $userId)->where('status', PengajuanSurat::STATUS_DIPROSES)->count();
        $revisi = PengajuanSurat::where('user_id', $userId)->where('status', PengajuanSurat::STATUS_REVISI)->count();
        $selesai = PengajuanSurat::where('user_id', $userId)->where('status', PengajuanSurat::STATUS_SELESAI)->count();
        
        // Get recent pengajuan (latest 5) - langsung dari MongoDB
        // WAJIB: where('user_id', $userId) untuk keamanan
        $recentPengajuan = PengajuanSurat::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('user.dashboard_user', compact(
            'user',
            'totalPengajuan',
            'menunggu',
            'diproses',
            'revisi',
            'selesai',
            'recentPengajuan'
        ));
    }
}

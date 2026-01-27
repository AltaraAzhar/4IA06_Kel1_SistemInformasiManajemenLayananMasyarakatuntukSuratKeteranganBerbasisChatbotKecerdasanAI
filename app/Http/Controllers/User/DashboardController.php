<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
        // Middleware sudah handle auth, langsung ambil user
        $user = Auth::user();
        $userId = $user->_id;
        
        // Query SELALU langsung dari MongoDB - tidak pernah pakai cache/session
        // WAJIB: where('user_id', $userId) untuk keamanan
        // Status SELALU fresh dari database
        $totalPengajuan = PengajuanSurat::where('user_id', $userId)->count();
        $menunggu = PengajuanSurat::where('user_id', $userId)->whereIn('status', ['diajukan', 'menunggu'])->count();
        $diproses = PengajuanSurat::where('user_id', $userId)->where('status', 'diproses')->count();
        $revisi = PengajuanSurat::where('user_id', $userId)->whereIn('status', ['direvisi', 'revisi'])->count();
        $selesai = PengajuanSurat::where('user_id', $userId)->where('status', 'selesai')->count();
        
        // Get recent pengajuan (latest 5) - SELALU langsung dari MongoDB
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


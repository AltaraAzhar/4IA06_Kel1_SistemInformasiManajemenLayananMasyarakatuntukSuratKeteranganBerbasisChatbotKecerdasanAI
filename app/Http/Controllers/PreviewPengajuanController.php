<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use Illuminate\Http\Request;

class PreviewPengajuanController extends Controller
{
    /**
     * Show preview pengajuan by token (public, no auth required)
     */
    public function show($token)
    {
        // Find pengajuan by preview_token
        $pengajuan = PengajuanSurat::where('preview_token', $token)->first();
        
        // Check if token exists
        if (!$pengajuan) {
            return view('preview.error', [
                'message' => 'Token tidak valid atau pengajuan tidak ditemukan.',
            ]);
        }
        
        // Check if token is expired
        if (!$pengajuan->isPreviewTokenValid()) {
            return view('preview.error', [
                'message' => 'Token preview telah kadaluarsa. Token hanya berlaku selama 7 hari.',
            ]);
        }
        
        return view('preview.show', compact('pengajuan'));
    }
}


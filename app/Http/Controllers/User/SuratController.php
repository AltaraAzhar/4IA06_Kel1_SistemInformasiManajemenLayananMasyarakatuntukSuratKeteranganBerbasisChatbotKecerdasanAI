<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SuratController extends Controller
{
    /**
     * Get all surat for logged-in user (JSON API)
     * WAJIB filter berdasarkan user_id yang login
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user || !$user->_id) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan. Silakan login terlebih dahulu.',
            ], 401);
        }
        
        // WAJIB filter user_id untuk keamanan - hanya ambil data user yang login
        $surat = PengajuanSurat::where('user_id', $user->_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $surat,
        ]);
    }

    /**
     * Display status pengajuan page for logged-in user
     * Status SELALU diambil langsung dari MongoDB (tidak pakai cache/session)
     * WAJIB filter berdasarkan user_id yang login
     */
    public function status()
    {
        // Middleware sudah handle auth, langsung ambil user
        $user = Auth::user();
        $userId = $user->_id;
        
        // Ambil data pengajuan berdasarkan user_id LANGSUNG dari MongoDB
        // SELALU fresh dari database - tidak pernah pakai cache/session
        // WAJIB filter user_id untuk keamanan
        $pengajuan = PengajuanSurat::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.status_surat_user', compact('pengajuan'));
    }

    /**
     * Store new pengajuan surat
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'jenis_layanan' => 'required|string|in:' . implode(',', PengajuanSurat::getSupportedLayanan()),
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|size:16',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'dokumen' => 'nullable|array',
        ]);

        // Generate nomor pengajuan
        $nomorPengajuan = PengajuanSurat::generateNomorPengajuan($validated['jenis_layanan']);

        // Generate etiket
        $etiket = PengajuanSurat::generateEtiket();
        
        // Generate preview token
        $previewToken = PengajuanSurat::generatePreviewToken();
        $previewExpiredAt = now()->addDays(7); // Token expired 7 hari
        
        // Pastikan user_id ada
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // Create pengajuan surat
        try {
            $pengajuan = PengajuanSurat::create([
                'user_id' => $userId,
                'jenis_layanan' => $validated['jenis_layanan'],
                'nama' => $validated['nama'],
                'nik' => $validated['nik'],
                'alamat' => $validated['alamat'],
                'no_hp' => $validated['no_hp'],
                'dokumen' => $validated['dokumen'] ?? [],
                'status' => 'diajukan',
                'etiket' => $etiket,
                'preview_token' => $previewToken,
                'preview_expired_at' => $previewExpiredAt,
            ]);
            
            // Pastikan data tersimpan
            if (!$pengajuan || !$pengajuan->_id) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal menyimpan pengajuan. Silakan coba lagi.');
            }
            
            // Simpan notifikasi
            try {
                \App\Models\Notification::create([
                    'user_id' => $userId,
                    'pengajuan_id' => $pengajuan->_id,
                    'message' => 'Pengajuan ' . $validated['jenis_layanan'] . ' berhasil diajukan. Etiket: ' . $etiket,
                    'is_read' => false,
                ]);
            } catch (\Exception $e) {
                \Log::error('Gagal menyimpan notifikasi: ' . $e->getMessage());
            }
            
            // Redirect dengan success message
            return redirect()
                ->route('user.surat.status')
                ->with('success', 'Permohonan surat berhasil diajukan dan sedang menunggu verifikasi')
                ->with('show_success_modal', true)
                ->with('pengajuan_data', [
                    'nomor_pengajuan' => $nomorPengajuan,
                    'jenis_layanan' => $validated['jenis_layanan'],
                    'etiket' => $etiket,
                    'nama' => $validated['nama'],
                    'tanggal' => now()->format('d F Y'),
                ]);
                
        } catch (\Exception $e) {
            \Log::error('Error menyimpan pengajuan: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan pengajuan. Silakan coba lagi.');
        }
    }

    /**
     * Download surat file
     * Hanya user yang memiliki pengajuan tersebut yang bisa download
     */
    public function download($id)
    {
        $user = Auth::user();
        
        if (!$user || !$user->_id) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cari pengajuan berdasarkan ID dan pastikan milik user yang login
        $pengajuan = PengajuanSurat::where('_id', $id)
            ->where('user_id', $user->_id)
            ->first();

        if (!$pengajuan) {
            return redirect()->route('user.surat.status')
                ->with('error', 'Pengajuan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Pastikan status sudah selesai
        if ($pengajuan->status !== 'selesai') {
            return redirect()->route('user.surat.status')
                ->with('error', 'Surat belum siap untuk didownload. Status pengajuan: ' . $pengajuan->status);
        }

        // Pastikan file surat ada
        if (!$pengajuan->file_surat) {
            return redirect()->route('user.surat.status')
                ->with('error', 'File surat belum tersedia. Silakan hubungi kelurahan.');
        }

        // Cek file ada di storage
        $filePath = 'surat/' . $pengajuan->file_surat;
        
        if (!Storage::disk('public')->exists($filePath)) {
            return redirect()->route('user.surat.status')
                ->with('error', 'File surat tidak ditemukan di server. Silakan hubungi kelurahan.');
        }

        // Download file
        try {
            $fullPath = Storage::disk('public')->path($filePath);
            $fileName = 'Surat_' . $pengajuan->jenis_layanan . '_' . $pengajuan->nomor_pengajuan . '.' . pathinfo($pengajuan->file_surat, PATHINFO_EXTENSION);
            
            return response()->download($fullPath, $fileName);
        } catch (\Exception $e) {
            \Log::error('Error downloading surat: ' . $e->getMessage());
            return redirect()->route('user.surat.status')
                ->with('error', 'Terjadi kesalahan saat mengunduh file. Silakan coba lagi.');
        }
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanSuratController extends Controller
{
    /**
     * Get all surat for logged-in user (JSON API)
     */
    /**
     * Get all surat for logged-in user (JSON API)
     * WAJIB filter berdasarkan user_id yang login
     */
    public function index()
    {
        $user = Auth::guard('web')->user();
        
        if (!$user || !$user->_id) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
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
     * Status diambil langsung dari MongoDB (tidak pakai cache/session)
     * WAJIB filter berdasarkan user_id yang login
     */
    public function status()
    {
        $user = Auth::guard('web')->user();
        
        if (!$user) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $userId = $user->_id;
        
        if (!$userId) {
            return redirect()->route('user.login')->with('error', 'Sesi Anda tidak valid. Silakan login ulang.');
        }
        
        // Ambil data pengajuan berdasarkan user_id langsung dari MongoDB
        // Pastikan status selalu fresh dari database
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
        
        // Pastikan user_id ada (menggunakan guard web)
        $userId = Auth::guard('web')->id();
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
                'status' => PengajuanSurat::STATUS_MENUNGGU,
                'etiket' => $etiket,
                'preview_token' => $previewToken, // Token untuk preview tanpa login
                'preview_expired_at' => $previewExpiredAt, // Token expired 7 hari
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
     * Get all surat (for admin)
     */
    public function adminIndex()
    {
        $surat = PengajuanSurat::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $surat,
        ]);
    }

    /**
     * Update status pengajuan surat
     */
    public function updateStatus(Request $request, $id)
    {
        // Validate request
        $validated = $request->validate([
            'status' => 'required|string|in:menunggu,diproses,revisi,selesai',
            'catatan_admin' => 'nullable|string',
        ]);

        // Find pengajuan surat
        $pengajuan = PengajuanSurat::findOrFail($id);

        // Update status
        $pengajuan->update([
            'status' => $validated['status'],
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pengajuan berhasil diupdate',
            'data' => $pengajuan,
        ]);
    }
}


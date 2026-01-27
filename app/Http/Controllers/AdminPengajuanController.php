<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use App\Models\PengajuanHistory;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminPengajuanController extends Controller
{
    /**
     * Display all pengajuan (admin dashboard)
     */
    public function index(Request $request)
    {
        $query = PengajuanSurat::with('user');

        // Filter by status
        $status = $request->get('status', 'all');
        
        if ($status === 'dalam_proses') {
            // Tampilkan status menunggu, diproses, dan revisi
            $query->whereIn('status', [
                PengajuanSurat::STATUS_MENUNGGU,
                PengajuanSurat::STATUS_DIPROSES,
                PengajuanSurat::STATUS_REVISI
            ]);
        } elseif ($status === 'menunggu') {
            $query->where('status', PengajuanSurat::STATUS_MENUNGGU);
        } elseif ($status === 'diproses') {
            $query->where('status', PengajuanSurat::STATUS_DIPROSES);
        } elseif ($status === 'revisi') {
            $query->where('status', PengajuanSurat::STATUS_REVISI);
        } elseif ($status === 'selesai') {
            $query->where('status', PengajuanSurat::STATUS_SELESAI);
        }
        // Jika 'all', tidak perlu filter status

        // Filter by jenis layanan
        $jenisLayanan = $request->get('jenis_layanan', 'all');
        
        if ($jenisLayanan !== 'all' && !empty($jenisLayanan)) {
            $query->where('jenis_layanan', $jenisLayanan);
        }

        // Search by nomor pengajuan, nama, jenis layanan, atau NIK
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_pengajuan', 'like', "%{$search}%")
                  ->orWhere('etiket', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('jenis_layanan', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics - ambil semua pengajuan (tidak terpengaruh filter)
        $stats = [
            'total' => PengajuanSurat::count(),
            'menunggu' => PengajuanSurat::where('status', PengajuanSurat::STATUS_MENUNGGU)->count(),
            'diproses' => PengajuanSurat::where('status', PengajuanSurat::STATUS_DIPROSES)->count(),
            'revisi' => PengajuanSurat::where('status', PengajuanSurat::STATUS_REVISI)->count(),
            'selesai' => PengajuanSurat::where('status', PengajuanSurat::STATUS_SELESAI)->count(),
        ];

        // Get unique jenis layanan for filter dropdown
        $jenisLayananList = PengajuanSurat::select('jenis_layanan')
            ->whereNotNull('jenis_layanan')
            ->where('jenis_layanan', '!=', '')
            ->distinct()
            ->orderBy('jenis_layanan')
            ->pluck('jenis_layanan')
            ->unique()
            ->values();

        // Selalu gunakan admin.dashboard
        return view('admin.dashboard', [
            'pengajuan' => $pengajuan,
            'totalPengajuan' => $stats['total'],
            'menunggu' => $stats['menunggu'],
            'diproses' => $stats['diproses'],
            'revisi' => $stats['revisi'],
            'selesai' => $stats['selesai'],
            'status' => $status,
            'jenis_layanan' => $jenisLayanan,
            'jenisLayananList' => $jenisLayananList,
            'search' => $request->get('search', ''),
        ]);
    }

    /**
     * Process pengajuan (ubah status dari menunggu → diproses)
     */
    public function process($id)
    {
        $pengajuan = PengajuanSurat::with('user')->findOrFail($id);
        
        // Validasi: hanya bisa proses jika status masih menunggu
        if ($pengajuan->status !== PengajuanSurat::STATUS_MENUNGGU) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Pengajuan ini sudah diproses atau memiliki status lain.');
        }

        $statusLama = $pengajuan->status;
        
        // Update status menjadi diproses
        $pengajuan->update([
            'status' => PengajuanSurat::STATUS_DIPROSES,
            'keterangan' => 'Sedang diproses oleh admin',
            'processed_at' => now(),
            'admin_id' => Auth::guard('web')->id() ?? session('admin_email'),
        ]);

        // Simpan riwayat
        $adminId = Auth::guard('web')->id() ?? (session('admin_email') ? 'admin_env' : null);
        PengajuanHistory::createHistory(
            $pengajuan->_id,
            $statusLama,
            PengajuanSurat::STATUS_DIPROSES,
            'process',
            'Pengajuan diproses oleh admin',
            $adminId
        );

        // Kirim notifikasi ke user
        Notification::create([
            'user_id' => $pengajuan->user_id,
            'pengajuan_id' => $pengajuan->_id,
            'message' => 'Pengajuan ' . $pengajuan->jenis_layanan . ' Anda sedang diproses.',
            'is_read' => false,
        ]);

        // Kirim email notifikasi
        $this->sendEmailNotification($pengajuan->user, $pengajuan, 'diproses');

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Pengajuan berhasil diproses.');
    }

    /**
     * Show documents (lihat dokumen pengajuan) - Return JSON for AJAX
     */
    public function showDocuments($id)
    {
        $pengajuan = PengajuanSurat::with('user')->findOrFail($id);
        
        // Prepare dokumen data dengan URL lengkap (public access)
        $dokumen = [];
        if ($pengajuan->dokumen && is_array($pengajuan->dokumen)) {
            foreach ($pengajuan->dokumen as $index => $doc) {
                $path = $doc['path'] ?? ($doc['url'] ?? '');
                // Jika path relatif, gunakan storage path
                if (strpos($path, 'http') !== 0) {
                    $fullPath = storage_path('app/public/' . ltrim($path, '/'));
                    $fileSize = file_exists($fullPath) ? filesize($fullPath) : ($doc['size'] ?? 0);
                    // Pastikan preview dokumen tetap lewat admin route
                    $url = route('admin.surat.documents.view', ['id' => $pengajuan->_id, 'index' => $index]);
                } else {
                    // Jika sudah full URL
                    $url = $path;
                    $fileSize = $doc['size'] ?? 0;
                }
                
                $dokumen[] = [
                    'name' => $doc['name'] ?? basename($path),
                    'path' => $path,
                    'url' => $url,
                    'size' => $fileSize,
                    'type' => $doc['type'] ?? pathinfo($path, PATHINFO_EXTENSION),
                ];
            }
        }

        return response()->json([
            'nomor_pengajuan' => $pengajuan->nomor_pengajuan,
            'dokumen' => $dokumen,
        ]);
    }

    /**
     * Preview dokumen via admin route (inline) - tetap di area admin
     */
    public function viewDocument($id, $index)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        $docs = is_array($pengajuan->dokumen) ? $pengajuan->dokumen : [];
        $doc = $docs[(int) $index] ?? null;
        if (!$doc || !is_array($doc)) {
            abort(404);
        }

        $path = $doc['path'] ?? ($doc['url'] ?? '');
        $name = $doc['name'] ?? (is_string($path) ? basename($path) : 'dokumen');

        // Jika path adalah URL eksternal, fallback redirect
        if (is_string($path) && str_starts_with($path, 'http')) {
            return redirect()->away($path);
        }

        if (!is_string($path) || $path === '') {
            abort(404);
        }

        // Normalisasi path agar cocok dengan disk public
        $normalized = ltrim($path, '/');
        if (str_starts_with($normalized, 'storage/')) {
            $normalized = substr($normalized, strlen('storage/'));
        }
        if (str_starts_with($normalized, 'public/')) {
            $normalized = substr($normalized, strlen('public/'));
        }

        if (!Storage::disk('public')->exists($normalized)) {
            abort(404);
        }

        return Storage::disk('public')->response($normalized, $name, [
            'Content-Disposition' => 'inline; filename="' . addslashes($name) . '"',
        ]);
    }

    /**
     * Show history (lihat riwayat pengajuan)
     */
    public function showHistory($id)
    {
        $pengajuan = PengajuanSurat::with('user')->findOrFail($id);
        
        // Get history dengan relasi admin jika ada
        $history = PengajuanHistory::where('pengajuan_id', $pengajuan->_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // If request wants JSON (for AJAX)
        if (request()->wantsJson() || request()->expectsJson()) {
            return response()->json([
                'pengajuan' => [
                    '_id' => $pengajuan->_id,
                    'nomor_pengajuan' => $pengajuan->nomor_pengajuan,
                    'jenis_layanan' => $pengajuan->jenis_layanan,
                    'nama' => $pengajuan->nama,
                    'nik' => $pengajuan->nik,
                    'alamat' => $pengajuan->alamat,
                    'no_hp' => $pengajuan->no_hp,
                    'status' => $pengajuan->status,
                    'keterangan' => $pengajuan->keterangan,
                    'created_at' => $pengajuan->created_at,
                    'user' => $pengajuan->user ? [
                        'name' => $pengajuan->user->name,
                        'email' => $pengajuan->user->email,
                    ] : null,
                ],
                'history' => $history->map(function($item) {
                    return [
                        '_id' => $item->_id,
                        'action' => $item->action,
                        'status_lama' => $item->status_lama,
                        'status_baru' => $item->status_baru,
                        'catatan' => $item->catatan,
                        'created_at' => $item->created_at,
                    ];
                }),
            ]);
        }

        return view('admin.pengajuan.history', compact('pengajuan', 'history'));
    }

    /**
     * Display detail pengajuan
     */
    public function show($id)
    {
        try {
            $pengajuan = PengajuanSurat::with('user')->findOrFail($id);
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->expectsJson() || request()->ajax()) {
                return response()->json(['error' => 'Pengajuan tidak ditemukan'], 404);
            }
            abort(404);
        }

        // If request wants JSON (for AJAX)
        if (request()->wantsJson() || request()->expectsJson() || request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            // Prepare dokumen with URLs
            $dokumen = [];
            if ($pengajuan->dokumen && is_array($pengajuan->dokumen)) {
                foreach ($pengajuan->dokumen as $index => $doc) {
                    $path = $doc['path'] ?? ($doc['url'] ?? '');
                    if (strpos($path, 'http') !== 0) {
                        $url = route('admin.surat.documents.view', ['id' => $pengajuan->_id, 'index' => $index]);
                    } else {
                        $url = $path;
                    }
                    
                    $dokumen[] = [
                        'name' => $doc['name'] ?? basename($path),
                        'path' => $path,
                        'url' => $url,
                        'size' => $doc['size'] ?? 0,
                        'type' => $doc['type'] ?? pathinfo($path, PATHINFO_EXTENSION),
                    ];
                }
            }
            
            return response()->json([
                'pengajuan' => [
                    '_id' => $pengajuan->_id,
                    'nomor_pengajuan' => $pengajuan->nomor_pengajuan,
                    'jenis_layanan' => $pengajuan->jenis_layanan,
                    'nama' => $pengajuan->nama,
                    'nik' => $pengajuan->nik,
                    'alamat' => $pengajuan->alamat,
                    'no_hp' => $pengajuan->no_hp,
                    'status' => $pengajuan->status,
                    'keterangan' => $pengajuan->keterangan,
                    'catatan_admin' => $pengajuan->catatan_admin,
                    'nomor_surat' => $pengajuan->nomor_surat,
                    'etiket' => $pengajuan->etiket,
                    'file_surat' => $pengajuan->file_surat,
                    'processed_at' => $pengajuan->processed_at,
                    'created_at' => $pengajuan->created_at,
                    'dokumen' => $dokumen,
                    'user' => $pengajuan->user ? [
                        'name' => $pengajuan->user->name,
                        'email' => $pengajuan->user->email,
                    ] : null,
                ],
            ]);
        }

        return view('admin.pengajuan.detail', compact('pengajuan'));
    }

    /**
     * Detail riwayat pengajuan user setelah selesai
     */
    public function detailRiwayat($id)
    {
        $pengajuan = PengajuanSurat::with('user')->findOrFail($id);
        
        // Get all pengajuan dari user yang sama
        $allPengajuan = PengajuanSurat::where('user_id', $pengajuan->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get history
        $history = PengajuanHistory::where('pengajuan_id', $pengajuan->_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // If request wants JSON (for AJAX)
        if (request()->wantsJson() || request()->expectsJson()) {
            return response()->json([
                'pengajuan' => $pengajuan,
                'all_pengajuan' => $allPengajuan,
                'history' => $history,
            ]);
        }
        
        return view('admin.pengajuan.detail-riwayat', compact('pengajuan', 'allPengajuan', 'history'));
    }

    /**
     * Selesaikan pengajuan (ubah status menjadi selesai)
     * Hanya bisa selesaikan jika status sudah diproses
     */
    public function selesai(Request $request, $id)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max, optional
        ], [
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
        ]);

        $pengajuan = PengajuanSurat::with('user')->findOrFail($id);

        // Validasi: hanya bisa selesaikan jika status sudah diproses
        if ($pengajuan->status !== PengajuanSurat::STATUS_DIPROSES) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Pengajuan harus diproses terlebih dahulu sebelum dapat diselesaikan.');
        }

        $statusLama = $pengajuan->status;
        
        // Generate nomor antrian / e-ticket jika belum ada
        $etiket = $pengajuan->etiket;
        if (!$etiket) {
            $etiket = PengajuanSurat::generateEtiket();
        }

        // Handle file upload (optional)
        $fileSurat = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $filename = 'surat_' . $pengajuan->nomor_pengajuan . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('surat', $filename, 'public');
            $fileSurat = $filename;
        }

        // Update status menjadi selesai
        $pengajuan->update([
            'status' => PengajuanSurat::STATUS_SELESAI,
            'etiket' => $etiket,
            'nomor_surat' => $validated['nomor_surat'],
            'file_surat' => $fileSurat,
            'keterangan' => 'Pengajuan selesai. Nomor surat: ' . $validated['nomor_surat'],
        ]);

        // Simpan riwayat
        $adminId = Auth::guard('web')->id() ?? (session('admin_email') ? 'admin_env' : null);
        PengajuanHistory::createHistory(
            $pengajuan->_id,
            $statusLama,
            PengajuanSurat::STATUS_SELESAI,
            'selesai',
            'Pengajuan selesai. Nomor surat: ' . $validated['nomor_surat'],
            $adminId
        );

        // Kirim notifikasi ke user
        Notification::create([
            'user_id' => $pengajuan->user_id,
            'pengajuan_id' => $pengajuan->_id,
            'message' => 'Pengajuan ' . $pengajuan->jenis_layanan . ' Anda telah selesai. Nomor surat: ' . $validated['nomor_surat'],
            'is_read' => false,
        ]);

        // Kirim email notifikasi
        $this->sendEmailNotification($pengajuan->user, $pengajuan, 'selesai', $etiket);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Pengajuan berhasil diselesaikan. Nomor surat: ' . $validated['nomor_surat']);
    }


    /**
     * Revise pengajuan (ubah status menjadi revisi dengan keterangan)
     */
    public function revise(Request $request, $id)
    {
        $validated = $request->validate([
            'keterangan' => 'required|string|max:1000',
        ], [
            'keterangan.required' => 'Keterangan wajib diisi.',
        ]);

        $pengajuan = PengajuanSurat::with('user')->findOrFail($id);

        // Validasi: hanya bisa revisi jika status menunggu atau diproses
        if (!in_array($pengajuan->status, [PengajuanSurat::STATUS_MENUNGGU, PengajuanSurat::STATUS_DIPROSES])) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Pengajuan tidak dapat direvisi pada status ini.');
        }

        $statusLama = $pengajuan->status;

        // Update status menjadi revisi
        $pengajuan->update([
            'status' => PengajuanSurat::STATUS_REVISI,
            'keterangan' => $validated['keterangan'],
            'catatan_admin' => $validated['keterangan'], // Simpan juga di catatan_admin untuk kompatibilitas
        ]);

        // Simpan riwayat
        $adminId = Auth::guard('web')->id() ?? (session('admin_email') ? 'admin_env' : null);
        PengajuanHistory::createHistory(
            $pengajuan->_id,
            $statusLama,
            PengajuanSurat::STATUS_REVISI,
            'revise',
            $validated['keterangan'],
            $adminId
        );

        // Kirim notifikasi ke user
        Notification::create([
            'user_id' => $pengajuan->user_id,
            'pengajuan_id' => $pengajuan->_id,
            'message' => 'Pengajuan ' . $pengajuan->jenis_layanan . ' Anda dikembalikan untuk revisi. Keterangan: ' . $validated['keterangan'],
            'is_read' => false,
        ]);

        // Kirim email notifikasi dengan keterangan revisi
        $this->sendEmailNotification($pengajuan->user, $pengajuan, 'revisi', null, $validated['keterangan']);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Pengajuan dikembalikan untuk revisi dan notifikasi telah dikirim ke user.');
    }

    /**
     * Helper method untuk mengirim email notifikasi
     */
    private function sendEmailNotification($user, $pengajuan, $status, $etiket = null, $catatan = null)
    {
        if (!$user || !$user->email) {
            return;
        }

        try {
            $subject = '';
            $message = '';

            switch ($status) {
                case 'diproses':
                    $subject = 'Pengajuan Surat ' . $pengajuan->jenis_layanan . ' Sedang Diproses';
                    $message = "Halo {$user->name},\n\n" .
                              "Pengajuan surat Anda sedang diproses:\n\n" .
                              "Nomor Pengajuan: {$pengajuan->nomor_pengajuan}\n" .
                              "Jenis Layanan: {$pengajuan->jenis_layanan}\n" .
                              "Status: Diproses\n\n" .
                              "Anda akan menerima notifikasi email ketika pengajuan Anda selesai.\n\n" .
                              "Terima kasih,\n" .
                              "Kelurahan Pabuaran Mekar";
                    break;

                case 'selesai':
                    $subject = 'Pengajuan Surat ' . $pengajuan->jenis_layanan . ' Selesai';
                    $message = "Halo {$user->name},\n\n" .
                              "Pengajuan surat Anda telah selesai:\n\n" .
                              "Nomor Pengajuan: {$pengajuan->nomor_pengajuan}\n" .
                              "Jenis Layanan: {$pengajuan->jenis_layanan}\n" .
                              "Nomor Antrian / E-Ticket: {$etiket}\n" .
                              "Status: Selesai\n\n" .
                              "Silakan datang ke kelurahan dengan membawa e-ticket untuk proses lanjutan.\n\n" .
                              "Terima kasih,\n" .
                              "Kelurahan Pabuaran Mekar";
                    break;

                case 'revisi':
                    $subject = 'Pengajuan Surat ' . $pengajuan->jenis_layanan . ' Perlu Revisi';
                    $message = "Halo {$user->name},\n\n" .
                              "Pengajuan surat Anda dikembalikan untuk revisi:\n\n" .
                              "Nomor Pengajuan: {$pengajuan->nomor_pengajuan}\n" .
                              "Jenis Layanan: {$pengajuan->jenis_layanan}\n" .
                              "Status: Perlu Revisi\n" .
                              "Catatan Perbaikan: {$catatan}\n\n" .
                              "Silakan perbaiki sesuai catatan dan ajukan kembali.\n\n" .
                              "Terima kasih,\n" .
                              "Kelurahan Pabuaran Mekar";
                    break;
            }

            Mail::raw($message, function ($mail) use ($user, $subject) {
                $mail->to($user->email)
                     ->subject($subject);
            });
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email notifikasi: ' . $e->getMessage());
            // Jangan gagalkan proses jika email gagal
        }
    }

    /**
     * Bulk update status (optional feature)
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|string',
            'status' => 'required|string|in:menunggu,diproses,revisi,selesai',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        $updated = 0;
        foreach ($validated['ids'] as $id) {
            $pengajuan = PengajuanSurat::find($id);
            if ($pengajuan) {
                $nomorAntrian = $pengajuan->nomor_antrian;
                if (in_array($validated['status'], [PengajuanSurat::STATUS_DIPROSES, PengajuanSurat::STATUS_SELESAI]) && !$nomorAntrian) {
                    $nomorAntrian = PengajuanSurat::generateNomorAntrian();
                }

                $pengajuan->update([
                    'status' => $validated['status'],
                    'nomor_antrian' => $nomorAntrian,
                    'catatan_admin' => $validated['catatan_admin'] ?? null,
                ]);
                $updated++;
            }
        }

        return redirect()
            ->route('admin.surat.index')
            ->with('success', "Berhasil mengupdate {$updated} pengajuan.");
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PengajuanController extends Controller
{
    /**
     * 5 LAYANAN SURAT yang tersedia
     */
    private function getLayananSurat()
    {
        return [
            'kelahiran' => [
                'nama' => 'Surat Keterangan Kelahiran',
                'deskripsi' => 'Surat pengantar untuk pengurusan Akta Kelahiran',
                'icon' => 'fa-baby',
                'persyaratan' => [
                    'Surat Pengantar RT/RW',
                    'Fotokopi Kartu Keluarga',
                    'Fotokopi KTP Orang Tua',
                    'Fotokopi Buku Nikah / Akta Perkawinan',
                    'Asli & Fotokopi Surat Keterangan Lahir RS/Bidan',
                ],
            ],
            'kematian' => [
                'nama' => 'Surat Keterangan Kematian',
                'deskripsi' => 'Surat pengantar untuk pengurusan Akta Kematian',
                'icon' => 'fa-dove',
                'persyaratan' => [
                    'Pengantar RT/RW',
                    'Fotokopi KTP dan KK Almarhum/Almarhumah',
                    'Fotokopi KTP dan KK Pelapor',
                    'Surat Keterangan Kematian dari Puskesmas/Dokter/Klinik/Rumah Sakit',
                    'Surat Pernyataan Kematian (jika meninggal di rumah)',
                    'Formulir Pelaporan Kematian',
                    'Foto Makam (opsional)',
                ],
            ],
            'usaha' => [
                'nama' => 'Surat Keterangan Usaha',
                'deskripsi' => 'Surat keterangan untuk usaha mikro dan kecil',
                'icon' => 'fa-store',
                'persyaratan' => [
                    'Pengantar RT/RW',
                    'Fotokopi Kartu Keluarga',
                    'Fotokopi KTP Pemohon',
                    'Foto Usaha',
                    'Izin Lingkungan + KTP Tetangga (Opsional)',
                    'Perjanjian Sewa / Kwitansi (Opsional)',
                    'Fotokopi SHM + Bukti Bayar PBB (Opsional)',
                ],
            ],
            'tidak-mampu' => [
                'nama' => 'Surat Keterangan Tidak Mampu',
                'deskripsi' => 'Surat keterangan tidak mampu untuk keringanan biaya pendidikan, kesehatan, dll',
                'icon' => 'fa-credit-card',
                'persyaratan' => [
                    'Pengantar RT/RW',
                    'Fotokopi KTP dan KK Pemohon',
                    'Surat Keterangan Rawat Inap (jika untuk UHC)',
                    'Surat Keterangan dari Sekolah (jika untuk PIP/Keringanan Biaya)',
                    'Surat Pernyataan Keluarga Miskin diketahui RT/RW dan bermaterai',
                ],
            ],
            'pbb' => [
                'nama' => 'Pengantar PBB',
                'deskripsi' => 'Surat pengantar untuk pengurusan Pajak Bumi dan Bangunan (PBB)',
                'icon' => 'fa-home',
                'persyaratan' => [
                    'Pengantar RT/RW',
                    'Fotokopi KTP',
                    'Fotokopi KK',
                    'Fotokopi Bukti Kepemilikan Tanah (Sertifikat/SHM)',
                    'SPPT PBB Tahun Sebelumnya (jika ada)',
                ],
            ],
        ];
    }

    /**
     * Halaman daftar layanan pengajuan
     * SECURITY: Hanya user dengan role 'user' yang boleh akses
     */
    public function index()
    {
        // Double protection: Check role even though middleware already handles it
        $user = Auth::user();
        if (!$user || $user->role !== 'user') {
            // If admin tries to access, redirect to admin dashboard
            if ($user && $user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Halaman pengajuan surat hanya dapat diakses oleh warga (user).');
            }
            // Otherwise, abort with 403
            abort(403, 'Akses ditolak. Hanya user yang dapat mengakses halaman ini.');
        }
        
        $layananSurat = $this->getLayananSurat();
        
        return view('pengajuan.index', compact('layananSurat'));
    }

    /**
     * Tampilkan modal info e-Tiket atau langsung ke form
     * SECURITY: Hanya user dengan role 'user' yang boleh akses
     */
    public function showForm($jenis)
    {
        // Double protection: Check role even though middleware already handles it
        $user = Auth::user();
        if (!$user || $user->role !== 'user') {
            // If admin tries to access, redirect to admin dashboard
            if ($user && $user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Halaman pengajuan surat hanya dapat diakses oleh warga (user).');
            }
            // Otherwise, abort with 403
            abort(403, 'Akses ditolak. Hanya user yang dapat mengakses halaman ini.');
        }
        
        $layananSurat = $this->getLayananSurat();
        
        // Validasi jenis surat
        if (!array_key_exists($jenis, $layananSurat)) {
            return redirect()->route('user.pengajuan')->with('error', 'Jenis surat tidak valid.');
        }
        
        $layanan = $layananSurat[$jenis];
        $layanan['slug'] = $jenis;
        
        // Cek apakah layanan memerlukan e-Tiket
        $memerlukanEtiket = in_array($layanan['nama'], PengajuanSurat::layananEtiket());
        
        // Jika Surat Keterangan Kelahiran, gunakan form khusus dengan stepper
        if ($jenis === 'kelahiran') {
            return view('pengajuan.form-kelahiran', compact('layanan'));
        }
        
        // Jika Surat Keterangan Kematian, gunakan form khusus dengan stepper
        if ($jenis === 'kematian') {
            return view('pengajuan.form-kematian', compact('layanan'));
        }
        
        // Jika Surat Keterangan Usaha, gunakan form khusus dengan stepper
        if ($jenis === 'usaha') {
            return view('pengajuan.form-usaha', compact('layanan'));
        }
        
        // Jika Surat Keterangan Tidak Mampu, gunakan form khusus dengan stepper
        if ($jenis === 'tidak-mampu') {
            return view('pengajuan.form-tidak-mampu', compact('layanan'));
        }
        
        // Untuk layanan lainnya (PBB), gunakan form.blade.php
        // Modal e-Ticket sudah terintegrasi di form.blade.php
        return view('pengajuan.form', compact('layanan'));
    }

    /**
     * Simpan pengajuan surat
     * SECURITY: Hanya user dengan role 'user' yang boleh submit pengajuan
     */
    public function store(Request $request, $jenis)
    {
        // Double protection: Check role even though middleware already handles it
        $user = Auth::user();
        if (!$user || $user->role !== 'user') {
            // If admin tries to access, redirect to admin dashboard
            if ($user && $user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Pengajuan surat hanya dapat dilakukan oleh warga (user).');
            }
            // Otherwise, abort with 403
            abort(403, 'Akses ditolak. Hanya user yang dapat mengajukan surat.');
        }
        
        $layananSurat = $this->getLayananSurat();
        
        // Validasi jenis surat
        if (!array_key_exists($jenis, $layananSurat)) {
            return redirect()->route('user.pengajuan')->with('error', 'Jenis surat tidak valid.');
        }
        
        // Validasi form - fleksibel untuk berbagai field name
        // Validasi field wajib secara manual karena field name berbeda-beda
        $rules = [
            // Field pelapor (bisa nama_lengkap atau nama_pelapor)
            'nama_lengkap' => 'nullable|string|max:255',
            'nama_pelapor' => 'nullable|string|max:255',
            // Field pembuat surat (untuk SKTM)
            'nama_pembuat_surat' => 'nullable|string|max:255',
            'nik_pembuat_surat' => 'nullable|string|max:16',
            'alamat_pembuat_surat' => 'nullable|string',
            // Field NIK (bisa nik atau nik_pelapor)
            'nik' => 'nullable|string|max:16',
            'nik_pelapor' => 'nullable|string|max:16',
            // Field alamat (bisa alamat atau alamat_pelapor)
            'alamat' => 'nullable|string',
            'alamat_pelapor' => 'nullable|string',
            // Field telepon (bisa no_hp, nomor_telepon, atau nomor_telepon_pelapor)
            'no_hp' => 'nullable|string|max:20',
            'nomor_telepon' => 'nullable|string|max:20',
            'nomor_telepon_pelapor' => 'nullable|string|max:20',
            // Field tambahan
            'nomor_kk' => 'nullable|string|max:16',
            'hubungan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ];
        
        // Tambahkan validasi untuk semua file yang mungkin dikirim
        // Validasi file akan dilakukan secara manual setelah ini
        $validated = $request->validate($rules);
        
        // Untuk SKTM, gunakan data pembuat surat (bukan pelapor)
        // Untuk layanan lain, gunakan data pelapor
        $namaSurat = $layananSurat[$jenis]['nama'];
        
        if ($jenis === 'tidak-mampu' || $namaSurat === 'Surat Keterangan Tidak Mampu') {
            // SKTM: Gunakan data pembuat surat
            $nama = $request->input('nama_pembuat_surat');
            $nik = $request->input('nik_pembuat_surat');
            $alamat = $request->input('alamat_pembuat_surat');
            // Untuk SKTM, no_hp bisa dari pelapor atau pembuat surat
            $noHp = $request->input('nomor_telepon_pelapor') ?? $request->input('nomor_telepon') ?? $request->input('no_hp');
        } else {
            // Layanan lain: Gunakan data pelapor dengan fallback
            $nama = $validated['nama_pelapor'] ?? $validated['nama_lengkap'] ?? $request->input('nama_pelapor') ?? $request->input('nama_lengkap');
            $nik = $validated['nik_pelapor'] ?? $validated['nik'] ?? $request->input('nik_pelapor') ?? $request->input('nik');
            $alamat = $validated['alamat_pelapor'] ?? $validated['alamat'] ?? $request->input('alamat_pelapor') ?? $request->input('alamat');
            $noHp = $validated['nomor_telepon_pelapor'] ?? $validated['nomor_telepon'] ?? $validated['no_hp'] ?? $request->input('nomor_telepon_pelapor') ?? $request->input('nomor_telepon') ?? $request->input('no_hp');
        }
        
        // Validasi ulang field wajib
        if (empty($nama) || empty($nik) || empty($alamat) || empty($noHp)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mohon lengkapi semua field wajib (Nama, NIK, Alamat, Nomor Telepon).');
        }
        
        // Handle file uploads - ambil semua file dari request
        $fileUploads = [];
        $allFiles = $request->allFiles();
        
        foreach ($allFiles as $key => $file) {
            // Validasi file secara manual
            if (is_object($file) && method_exists($file, 'getClientOriginalName')) {
                // Validasi tipe file
                $allowedMimes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
                
                $mimeType = $file->getMimeType();
                $extension = strtolower($file->getClientOriginalExtension());
                
                if (!in_array($mimeType, $allowedMimes) && !in_array($extension, $allowedExtensions)) {
                    continue; // Skip file yang tidak valid
                }
                
                // Validasi ukuran (max 2MB)
                if ($file->getSize() > 2048 * 1024) {
                    continue; // Skip file yang terlalu besar
                }
                
                // SECURITY: Sanitize filename to prevent path traversal
                $originalName = $file->getClientOriginalName();
                $sanitizedName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($originalName));
                $sanitizedName = substr($sanitizedName, 0, 255); // Limit length
                $filename = time() . '_' . uniqid() . '_' . $sanitizedName;
                
                // SECURITY: Validate real file content, not just extension
                $realMimeType = mime_content_type($file->getRealPath());
                if (!in_array($realMimeType, $allowedMimes)) {
                    continue; // Skip if real MIME doesn't match
                }
                
                $path = $file->storeAs('pengajuan', $filename, 'public');
                $fileUploads[] = $path;
            }
            // Handle array of files
            elseif (is_array($file)) {
                foreach ($file as $index => $singleFile) {
                    if (is_object($singleFile) && method_exists($singleFile, 'getClientOriginalName')) {
                        // Validasi tipe file
                        $allowedMimes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
                        
                        $mimeType = $singleFile->getMimeType();
                        $extension = strtolower($singleFile->getClientOriginalExtension());
                        
                        if (!in_array($mimeType, $allowedMimes) && !in_array($extension, $allowedExtensions)) {
                            continue;
                        }
                        
                        // Validasi ukuran (max 2MB)
                        if ($singleFile->getSize() > 2048 * 1024) {
                            continue;
                        }
                        
                        // SECURITY: Sanitize filename to prevent path traversal
                        $originalName = $singleFile->getClientOriginalName();
                        $sanitizedName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($originalName));
                        $sanitizedName = substr($sanitizedName, 0, 255); // Limit length
                        $filename = time() . '_' . uniqid() . '_' . $index . '_' . $sanitizedName;
                        
                        // SECURITY: Validate real file content, not just extension
                        $realMimeType = mime_content_type($singleFile->getRealPath());
                        if (!in_array($realMimeType, $allowedMimes)) {
                            continue; // Skip if real MIME doesn't match
                        }
                        
                        $path = $singleFile->storeAs('pengajuan', $filename, 'public');
                        $fileUploads[] = $path;
                    }
                }
            }
        }
        
        // Generate nomor pengajuan, etiket, dan preview token
        $nomorPengajuan = PengajuanSurat::generateNomorPengajuan($namaSurat);
        
        // Generate etiket dengan format khusus untuk SKTM
        if ($namaSurat === 'Surat Keterangan Tidak Mampu') {
            // Untuk SKTM, gunakan format: SKTM:XXXXXXXXXXXX-XXX
            // NIK pembuat surat sudah diambil di atas
            $etiket = PengajuanSurat::generateEtiket($namaSurat, $nik);
        } else {
            $etiket = PengajuanSurat::generateEtiket();
        }
        
        $previewToken = PengajuanSurat::generatePreviewToken();
        $previewExpiredAt = now()->addDays(7); // Token expired 7 hari
        
        // Prepare dokumen array dengan informasi lengkap
        $dokumenArray = [];
        foreach ($fileUploads as $index => $path) {
            $fullPath = storage_path('app/public/' . $path);
            $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
            
            $dokumenArray[] = [
                'name' => basename($path),
                'path' => $path,
                'url' => asset('storage/' . $path), // URL untuk akses file
                'size' => $fileSize, // Ukuran file dalam bytes
                'type' => pathinfo($path, PATHINFO_EXTENSION), // Extension file
            ];
        }
        
        // Pastikan user_id ada (menggunakan guard web)
        $userId = Auth::guard('web')->id();
        if (!$userId) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // Simpan ke database MongoDB
        try {
            $pengajuan = PengajuanSurat::create([
                'user_id' => $userId,
                'nomor_pengajuan' => $nomorPengajuan, // Nomor pengajuan unik
                'jenis_layanan' => $namaSurat,
                'nama' => $nama,
                'nik' => $nik,
                'alamat' => $alamat,
                'no_hp' => $noHp,
                'dokumen' => $dokumenArray,
                'status' => 'diajukan', // Status awal: diajukan (sesuai requirement)
                'etiket' => $etiket, // e-ticket / nomor antrian
                'preview_token' => $previewToken, // Token untuk preview tanpa login
                'preview_expired_at' => $previewExpiredAt, // Token expired 7 hari
                'created_at' => now(),
            ]);
            
            // Pastikan data tersimpan
            if (!$pengajuan || !$pengajuan->_id) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal menyimpan pengajuan. Silakan coba lagi.');
            }
            
            // Simpan notifikasi sukses ke MongoDB
            try {
                Notification::create([
                    'user_id' => $userId,
                    'pengajuan_id' => $pengajuan->_id,
                    'message' => 'Pengajuan ' . $namaSurat . ' berhasil diajukan. Nomor pengajuan: ' . $nomorPengajuan,
                    'is_read' => false,
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal menyimpan notifikasi: ' . $e->getMessage());
            }
            
            // Kirim email notifikasi ke user
            try {
                $user = User::find($userId);
                if ($user && $user->email) {
                    Mail::raw(
                        "Halo {$user->name},\n\n" .
                        "Pengajuan surat Anda telah berhasil diterima:\n\n" .
                        "Nomor Pengajuan: {$nomorPengajuan}\n" .
                        "Jenis Layanan: {$namaSurat}\n" .
                        "Status: Menunggu Verifikasi\n\n" .
                        "Anda akan menerima notifikasi email ketika pengajuan Anda diproses.\n\n" .
                        "Terima kasih,\n" .
                        "Kelurahan Pabuaran Mekar",
                        function ($message) use ($user, $namaSurat) {
                            $message->to($user->email)
                                    ->subject('Pengajuan Surat ' . $namaSurat . ' Berhasil Diterima');
                        }
                    );
                }
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email: ' . $e->getMessage());
                // Jangan gagalkan pengajuan jika email gagal
            }
            
            // Redirect dengan success message dan data untuk modal
            // Semua layanan (termasuk SKTM dan Kelahiran) redirect ke status page dengan notifikasi sukses
            return redirect()
                ->route('user.surat.status')
                ->with('success', 'Permohonan surat berhasil diajukan dan sedang menunggu verifikasi')
                ->with('show_success_modal', true)
                ->with('pengajuan_data', [
                    'nomor_pengajuan' => $nomorPengajuan,
                    'jenis_layanan' => $namaSurat,
                    'etiket' => $etiket,
                    'nama' => $nama,
                    'tanggal' => now()->format('d F Y'),
                ]);
                
        } catch (\Exception $e) {
            Log::error('Error menyimpan pengajuan: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan pengajuan. Silakan coba lagi.');
        }
    }

    /**
     * Halaman status pengajuan
     */
    public function status()
    {
        $userId = Auth::guard('web')->id();
        
        $pengajuan = PengajuanSurat::where('user_id', $userId)
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(10);
        
        return view('user.status_surat_user', compact('pengajuan'));
    }

    /**
     * Download document PDF
     * SECURITY: Hanya user dengan role 'user' yang boleh download dokumen
     * 
     * @param string $document Document key identifier
     * @return BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function downloadDocument($document)
    {
        // Mapping document keys to file names
        $documentMap = [
            'spbpm_calon1' => 'Surat_Pernyataan_Belum_Pernah_Menikah_Calon_Pengantin_1.pdf',
            'spbpm_calon2' => 'Surat_Pernyataan_Belum_Pernah_Menikah_Calon_Pengantin_2.pdf',
            'sppm' => 'Surat_Pernyataan_Pernah_Menikah.pdf',
            'n2' => 'Formulir_Permohonan_Kehendak_Nikah_N2.pdf',
            'n4' => 'Persetujuan_Calon_Pengantin_N4.pdf',
            'n5' => 'Surat_Izin_Orang_Tua_N5.pdf',
            'spwn' => 'Surat_Pernyataan_Wali_Nikah.pdf',
            'spkkm' => 'Surat_Pernyataan_Keluarga_Kurang_Mampu.pdf',
            'fskd' => 'Formulir_Surat_Keterangan_Domisili.pdf',
        ];

        // Validate document key
        if (!isset($documentMap[$document])) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
        }

        $filename = $documentMap[$document];
        $filePath = storage_path('app/public/documents/' . $filename);

        // Check if file exists
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File dokumen belum tersedia. Silakan hubungi administrator.');
        }

        // Return file download response
        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(false);
    }
}

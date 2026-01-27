# Contoh Kode MongoDB untuk Sistem Layanan Kelurahan

## 📋 Daftar Isi
1. [Contoh Controller Methods](#contoh-controller-methods)
2. [Contoh Tinker Commands](#contoh-tinker-commands)
3. [Contoh Query](#contoh-query)

---

## 🎮 Contoh Controller Methods

### 1. Menyimpan Pengajuan Baru

**File:** `app/Http/Controllers/PengajuanController.php`

```php
/**
 * Simpan pengajuan surat baru
 */
public function store(Request $request, $jenis)
{
    $layananSurat = $this->getLayananSurat();
    
    // Validasi jenis surat
    if (!array_key_exists($jenis, $layananSurat)) {
        return redirect()->route('pengajuan')
            ->with('error', 'Jenis surat tidak valid.');
    }
    
    // Validasi form
    $validated = $request->validate([
        // Data Pelapor
        'nama_pelapor' => 'required|string|max:255',
        'nik_pelapor' => 'required|string|size:16',
        'nomor_kk' => 'nullable|string|size:16',
        'alamat_lengkap' => 'required|string',
        'nomor_telepon' => 'required|string|max:20',
        'hubungan_dengan_pembuat_surat' => 'required|string',
        
        // Data Pembuat Surat (jika berbeda dengan pelapor)
        'nama_pembuat_surat' => 'nullable|string|max:255',
        'nik_pembuat_surat' => 'nullable|string|size:16',
        'alamat_ktp' => 'nullable|string',
        
        // File uploads
        'doc_pengantar_rt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'doc_ktp_kk_alm' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        // ... tambahkan validasi untuk dokumen lain sesuai kebutuhan
    ]);
    
    // Handle file uploads
    $dokumenUpload = [];
    $uploadFields = [
        'doc_pengantar_rt' => 'Surat Pengantar RT/RW',
        'doc_ktp_kk_alm' => 'Fotokopi KTP dan KK Almarhum/Almarhumah',
        // ... tambahkan mapping dokumen lain
    ];
    
    foreach ($uploadFields as $fieldKey => $fieldName) {
        if ($request->hasFile($fieldKey)) {
            $file = $request->file($fieldKey);
            $filename = time() . '_' . $fieldKey . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('pengajuan', $filename, 'public');
            
            $dokumenUpload[] = [
                'key' => $fieldKey,
                'name' => $fieldName,
                'path' => $path,
                'type' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'required' => true,
                'uploaded_at' => now()->toDateTimeString(),
            ];
        }
    }
    
    // Generate nomor pengajuan
    $noPengajuan = PengajuanSurat::generateNoPengajuan();
    
    // Cek apakah layanan ini memerlukan e-Tiket
    $namaSurat = $layananSurat[$jenis]['nama'];
    $memerlukanEtiket = in_array($namaSurat, PengajuanSurat::layananEtiket());
    
    // Generate nomor e-Tiket jika diperlukan
    $nomorTiket = null;
    $statusTiket = null;
    if ($memerlukanEtiket) {
        $nomorTiket = PengajuanSurat::generateNomorTiket();
        $statusTiket = 'Menunggu Verifikasi';
    }
    
    // Prepare data pelapor
    $dataPelapor = [
        'nama_pelapor' => $validated['nama_pelapor'],
        'nik_pelapor' => $validated['nik_pelapor'],
        'nomor_kk' => $validated['nomor_kk'] ?? null,
        'alamat_lengkap' => $validated['alamat_lengkap'],
        'nomor_telepon' => $validated['nomor_telepon'],
        'hubungan_dengan_pembuat_surat' => $validated['hubungan_dengan_pembuat_surat'],
    ];
    
    // Prepare data pembuat surat (jika ada)
    $dataPembuatSurat = null;
    if (!empty($validated['nama_pembuat_surat'])) {
        $dataPembuatSurat = [
            'nama_pembuat_surat' => $validated['nama_pembuat_surat'],
            'nik_pembuat_surat' => $validated['nik_pembuat_surat'],
            'alamat_ktp' => $validated['alamat_ktp'],
        ];
    }
    
    // Simpan ke database
    $pengajuan = PengajuanSurat::create([
        'user_id' => auth()->id(),
        'jenis_surat' => $namaSurat,
        'slug_layanan' => $jenis,
        'no_pengajuan' => $noPengajuan,
        'data_pelapor' => $dataPelapor,
        'data_pembuat_surat' => $dataPembuatSurat,
        'dokumen_upload' => $dokumenUpload,
        'status' => PengajuanSurat::STATUS_MENUNGGU,
        'tanggal_pengajuan' => now(),
        'estimasi_selesai' => now()->addDays(3),
        'keterangan' => 'Pengajuan baru menunggu verifikasi',
        'memerlukan_etiket' => $memerlukanEtiket,
        'nomor_tiket' => $nomorTiket,
        'status_tiket' => $statusTiket,
        'created_by' => auth()->id(),
    ]);
    
    // Redirect dengan success message
    return redirect()->route('pengajuan.status')
        ->with('success', 'Pengajuan berhasil! Nomor pengajuan: ' . $noPengajuan)
        ->with('pengajuan_id', $pengajuan->_id);
}
```

---

### 2. Menampilkan Pengajuan User Login

**File:** `app/Http/Controllers/PengajuanController.php`

```php
/**
 * Halaman status pengajuan user
 */
public function status()
{
    $userId = auth()->id();
    
    // Query pengajuan milik user yang login
    $pengajuan = PengajuanSurat::where('user_id', $userId)
        ->with('user') // Eager load relasi user
        ->orderBy('tanggal_pengajuan', 'desc')
        ->paginate(10);
    
    return view('pengajuan.status', compact('pengajuan'));
}

/**
 * Detail pengajuan spesifik
 */
public function show($id)
{
    $pengajuan = PengajuanSurat::where('_id', $id)
        ->where('user_id', auth()->id()) // Pastikan hanya milik user yang login
        ->with('user')
        ->firstOrFail();
    
    return view('pengajuan.detail', compact('pengajuan'));
}
```

---

### 3. Update Status oleh Admin

**File:** `app/Http/Controllers/AdminPengajuanController.php`

```php
/**
 * Update status pengajuan menjadi "Diproses"
 */
public function process($id)
{
    $pengajuan = PengajuanSurat::findOrFail($id);
    
    $pengajuan->update([
        'status' => PengajuanSurat::STATUS_DIPROSES,
        'tanggal_diproses' => now(),
        'processed_by' => auth()->id(),
        'keterangan' => 'Pengajuan sedang diproses oleh admin',
    ]);
    
    return back()->with('success', 'Pengajuan berhasil diproses.');
}

/**
 * Selesaikan pengajuan dengan nomor surat
 */
public function complete(Request $request, $id)
{
    $validated = $request->validate([
        'nomor_surat' => 'required|string|max:255',
        'file_surat' => 'nullable|file|mimes:pdf|max:10240',
    ]);
    
    $pengajuan = PengajuanSurat::findOrFail($id);
    
    $updateData = [
        'status' => PengajuanSurat::STATUS_SELESAI,
        'tanggal_selesai' => now(),
        'nomor_surat' => $validated['nomor_surat'],
        'tanggal_surat' => now(),
        'completed_by' => auth()->id(),
        'keterangan' => 'Pengajuan selesai dan surat sudah dibuat',
    ];
    
    // Upload file surat jika ada
    if ($request->hasFile('file_surat')) {
        $file = $request->file('file_surat');
        $filename = 'surat_' . $pengajuan->no_pengajuan . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('surat', $filename, 'public');
        $updateData['file_surat_path'] = $path;
    }
    
    // Update status e-Tiket jika diperlukan
    if ($pengajuan->memerlukan_etiket) {
        $updateData['status_tiket'] = 'Disetujui';
        $updateData['tanggal_tiket'] = now();
        $updateData['jam_tiket'] = now()->format('H:i');
    }
    
    $pengajuan->update($updateData);
    
    return back()->with('success', 'Pengajuan berhasil diselesaikan.');
}

/**
 * Tolak pengajuan
 */
public function reject(Request $request, $id)
{
    $validated = $request->validate([
        'alasan_penolakan' => 'required|string|min:10',
    ]);
    
    $pengajuan = PengajuanSurat::findOrFail($id);
    
    $pengajuan->update([
        'status' => PengajuanSurat::STATUS_DITOLAK,
        'tanggal_ditolak' => now(),
        'alasan_penolakan' => $validated['alasan_penolakan'],
        'keterangan' => 'Pengajuan ditolak: ' . $validated['alasan_penolakan'],
        'processed_by' => auth()->id(),
    ]);
    
    // Update status e-Tiket jika ada
    if ($pengajuan->memerlukan_etiket) {
        $pengajuan->update([
            'status_tiket' => 'Ditolak',
        ]);
    }
    
    return back()->with('success', 'Pengajuan ditolak.');
}
```

---

## 💻 Contoh Tinker Commands

### Buka Tinker

```bash
php artisan tinker
```

### 1. Insert Data Dummy - Pengajuan Baru

```php
use App\Models\PengajuanSurat;
use App\Models\User;

// Ambil user pertama (atau buat dummy user)
$user = User::first();
// Atau buat user dummy:
// $user = User::create([
//     'name' => 'Budi Santoso',
//     'email' => 'budi@example.com',
//     'nik_or_nip' => '3201010101010001',
//     'password' => bcrypt('password'),
//     'role' => 'user',
// ]);

// Insert pengajuan Surat Keterangan Kematian
$pengajuan = PengajuanSurat::create([
    'user_id' => $user->_id,
    'jenis_surat' => 'Surat Keterangan Kematian',
    'slug_layanan' => 'kematian',
    'no_pengajuan' => PengajuanSurat::generateNoPengajuan(),
    'data_pelapor' => [
        'nama_pelapor' => 'Budi Santoso',
        'nik_pelapor' => '3201010101010001',
        'nomor_kk' => '3201010101010001',
        'alamat_lengkap' => 'Jl. Raya Pabuaran Mekar No. 123, RT 01/RW 05, Kelurahan Pabuaran Mekar, Kecamatan Cibinong',
        'nomor_telepon' => '081234567890',
        'hubungan_dengan_pembuat_surat' => 'Anak',
    ],
    'data_pembuat_surat' => [
        'nama_pembuat_surat' => 'Budi Santoso',
        'nik_pembuat_surat' => '3201010101010001',
        'alamat_ktp' => 'Jl. Raya Pabuaran Mekar No. 123, RT 01/RW 05',
    ],
    'dokumen_upload' => [
        [
            'key' => 'doc_pengantar_rt',
            'name' => 'Surat Pengantar RT/RW',
            'path' => 'storage/pengajuan/dummy_pengantar_rt.pdf',
            'type' => 'pdf',
            'size' => 2048000,
            'required' => true,
            'uploaded_at' => now()->toDateTimeString(),
        ],
    ],
    'status' => PengajuanSurat::STATUS_MENUNGGU,
    'tanggal_pengajuan' => now(),
    'estimasi_selesai' => now()->addDays(3),
    'keterangan' => 'Pengajuan baru menunggu verifikasi',
    'memerlukan_etiket' => true,
    'nomor_tiket' => PengajuanSurat::generateNomorTiket(),
    'status_tiket' => 'Menunggu Verifikasi',
    'created_by' => $user->_id,
]);

echo "Pengajuan berhasil dibuat dengan ID: " . $pengajuan->_id . "\n";
echo "Nomor Pengajuan: " . $pengajuan->no_pengajuan . "\n";
echo "Nomor Tiket: " . $pengajuan->nomor_tiket . "\n";
```

### 2. Insert Multiple Pengajuan (Dummy Data)

```php
use App\Models\PengajuanSurat;
use App\Models\User;

$user = User::first();

$layananList = [
    [
        'jenis_surat' => 'Surat Keterangan Kelahiran',
        'slug' => 'kelahiran',
        'memerlukan_etiket' => false,
    ],
    [
        'jenis_surat' => 'Surat Keterangan Kematian',
        'slug' => 'kematian',
        'memerlukan_etiket' => true,
    ],
    [
        'jenis_surat' => 'Surat Keterangan Usaha',
        'slug' => 'usaha',
        'memerlukan_etiket' => true,
    ],
    [
        'jenis_surat' => 'Surat Keterangan Tidak Mampu',
        'slug' => 'tidak-mampu',
        'memerlukan_etiket' => false,
    ],
    [
        'jenis_surat' => 'Pengantar PBB',
        'slug' => 'pbb',
        'memerlukan_etiket' => true,
    ],
];

foreach ($layananList as $layanan) {
    $pengajuan = PengajuanSurat::create([
        'user_id' => $user->_id,
        'jenis_surat' => $layanan['jenis_surat'],
        'slug_layanan' => $layanan['slug'],
        'no_pengajuan' => PengajuanSurat::generateNoPengajuan(),
        'data_pelapor' => [
            'nama_pelapor' => 'Budi Santoso',
            'nik_pelapor' => '3201010101010001',
            'nomor_kk' => '3201010101010001',
            'alamat_lengkap' => 'Jl. Raya Pabuaran Mekar No. 123',
            'nomor_telepon' => '081234567890',
            'hubungan_dengan_pembuat_surat' => 'Diri Sendiri',
        ],
        'status' => PengajuanSurat::STATUS_MENUNGGU,
        'tanggal_pengajuan' => now(),
        'estimasi_selesai' => now()->addDays(3),
        'keterangan' => 'Pengajuan dummy untuk testing',
        'memerlukan_etiket' => $layanan['memerlukan_etiket'],
        'nomor_tiket' => $layanan['memerlukan_etiket'] ? PengajuanSurat::generateNomorTiket() : null,
        'status_tiket' => $layanan['memerlukan_etiket'] ? 'Menunggu Verifikasi' : null,
        'created_by' => $user->_id,
    ]);
    
    echo "Created: " . $pengajuan->jenis_surat . " - " . $pengajuan->no_pengajuan . "\n";
}

echo "Semua pengajuan dummy berhasil dibuat!\n";
```

### 3. Query Pengajuan User

```php
use App\Models\PengajuanSurat;
use App\Models\User;

$user = User::first();

// Semua pengajuan user
$pengajuan = PengajuanSurat::where('user_id', $user->_id)->get();
echo "Total pengajuan: " . $pengajuan->count() . "\n";

// Pengajuan dengan status tertentu
$menunggu = PengajuanSurat::where('user_id', $user->_id)
    ->where('status', 'menunggu')
    ->get();
echo "Pengajuan menunggu: " . $menunggu->count() . "\n";

// Pengajuan yang memerlukan e-Tiket
$denganEtiket = PengajuanSurat::where('user_id', $user->_id)
    ->where('memerlukan_etiket', true)
    ->get();
echo "Pengajuan dengan e-Tiket: " . $denganEtiket->count() . "\n";
```

### 4. Update Status Pengajuan

```php
use App\Models\PengajuanSurat;

// Update status menjadi "Diproses"
$pengajuan = PengajuanSurat::where('status', 'menunggu')->first();
if ($pengajuan) {
    $pengajuan->update([
        'status' => PengajuanSurat::STATUS_DIPROSES,
        'tanggal_diproses' => now(),
        'keterangan' => 'Sedang diproses oleh admin',
    ]);
    echo "Status updated: " . $pengajuan->no_pengajuan . "\n";
}

// Selesaikan pengajuan
$pengajuan = PengajuanSurat::where('status', 'diproses')->first();
if ($pengajuan) {
    $pengajuan->update([
        'status' => PengajuanSurat::STATUS_SELESAI,
        'tanggal_selesai' => now(),
        'nomor_surat' => 'SKM-2025-0001',
        'tanggal_surat' => now(),
        'keterangan' => 'Pengajuan selesai',
    ]);
    
    if ($pengajuan->memerlukan_etiket) {
        $pengajuan->update([
            'status_tiket' => 'Disetujui',
            'tanggal_tiket' => now(),
            'jam_tiket' => now()->format('H:i'),
        ]);
    }
    
    echo "Pengajuan selesai: " . $pengajuan->no_pengajuan . "\n";
}
```

### 5. Query untuk Admin Dashboard

```php
use App\Models\PengajuanSurat;

// Statistik umum
$total = PengajuanSurat::count();
$menunggu = PengajuanSurat::where('status', 'menunggu')->count();
$diproses = PengajuanSurat::where('status', 'diproses')->count();
$selesai = PengajuanSurat::where('status', 'selesai')->count();
$ditolak = PengajuanSurat::where('status', 'ditolak')->count();

echo "Total: $total\n";
echo "Menunggu: $menunggu\n";
echo "Diproses: $diproses\n";
echo "Selesai: $selesai\n";
echo "Ditolak: $ditolak\n";

// Pengajuan terbaru
$terbaru = PengajuanSurat::orderBy('tanggal_pengajuan', 'desc')
    ->limit(5)
    ->get();

foreach ($terbaru as $p) {
    echo $p->no_pengajuan . " - " . $p->jenis_surat . " - " . $p->status . "\n";
}

// Pengajuan per jenis layanan
$perJenis = PengajuanSurat::raw(function($collection) {
    return $collection->aggregate([
        ['$group' => [
            '_id' => '$jenis_surat',
            'count' => ['$sum' => 1]
        ]],
        ['$sort' => ['count' => -1]]
    ]);
});

foreach ($perJenis as $item) {
    echo $item['_id'] . ": " . $item['count'] . "\n";
}
```

### 6. Hapus Data Dummy (Testing)

```php
use App\Models\PengajuanSurat;

// Hapus semua pengajuan dengan keterangan "dummy"
$deleted = PengajuanSurat::where('keterangan', 'like', '%dummy%')
    ->orWhere('keterangan', 'like', '%testing%')
    ->delete();

echo "Deleted $deleted pengajuan dummy\n";

// Atau hapus semua (HATI-HATI!)
// PengajuanSurat::truncate();
// echo "Semua pengajuan dihapus!\n";
```

---

## 🔍 Contoh Query Lanjutan

### Query dengan Filter Kompleks

```php
use App\Models\PengajuanSurat;

// Pengajuan dalam rentang tanggal
$startDate = now()->subDays(7);
$endDate = now();

$pengajuan = PengajuanSurat::whereBetween('tanggal_pengajuan', [$startDate, $endDate])
    ->where('status', '!=', 'ditolak')
    ->orderBy('tanggal_pengajuan', 'desc')
    ->get();

// Pengajuan dengan e-Tiket yang sudah disetujui
$etiketDisetujui = PengajuanSurat::where('memerlukan_etiket', true)
    ->where('status_tiket', 'Disetujui')
    ->where('status', 'selesai')
    ->get();

// Pengajuan berdasarkan NIK pelapor (dari data_pelapor)
$pengajuanByNIK = PengajuanSurat::where('data_pelapor.nik_pelapor', '3201010101010001')
    ->get();
```

### Query dengan Relasi User

```php
use App\Models\PengajuanSurat;

// Pengajuan dengan data user
$pengajuan = PengajuanSurat::with('user')
    ->where('status', 'menunggu')
    ->get();

foreach ($pengajuan as $p) {
    echo $p->no_pengajuan . " - " . $p->user->name . " - " . $p->user->email . "\n";
}
```

---

## ✅ Verifikasi Connection MongoDB

### Test Connection di Tinker

```php
// Test connection
try {
    $pengajuan = PengajuanSurat::count();
    echo "Connection OK! Total pengajuan: $pengajuan\n";
} catch (\Exception $e) {
    echo "Connection Error: " . $e->getMessage() . "\n";
}

// Test insert sederhana
try {
    $test = PengajuanSurat::create([
        'user_id' => 'test',
        'jenis_surat' => 'Test',
        'no_pengajuan' => 'TEST-001',
        'status' => 'menunggu',
        'tanggal_pengajuan' => now(),
    ]);
    echo "Insert test berhasil! ID: " . $test->_id . "\n";
    
    // Hapus test data
    $test->delete();
    echo "Test data dihapus.\n";
} catch (\Exception $e) {
    echo "Insert Error: " . $e->getMessage() . "\n";
}
```

---

## 📝 Catatan Penting

1. **Gunakan `auth()->id()`** untuk mendapatkan user_id saat user login
2. **Gunakan `ObjectId`** untuk relasi, Laravel otomatis handle konversi
3. **Array fields** seperti `data_pelapor` dan `dokumen_upload` otomatis di-cast ke array
4. **Timestamps** otomatis di-handle oleh Laravel (`created_at`, `updated_at`)
5. **Collection dibuat otomatis** saat insert pertama kali, tidak perlu migration

---

## 🚀 Next Steps

1. Test semua contoh kode di Tinker
2. Implementasi di Controller sesuai kebutuhan
3. Buat index di MongoDB untuk performa optimal
4. Setup validation rules yang sesuai
5. Implementasi notification system (email/SMS)


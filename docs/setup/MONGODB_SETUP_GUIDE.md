# Panduan Setup MongoDB Atlas untuk Laravel

## 🚀 Quick Start

### 1. Install Package MongoDB Laravel

```bash
composer require mongodb/laravel-mongodb
```

### 2. Konfigurasi `.env`

Tambahkan konfigurasi berikut di file `.env`:

```env
# MongoDB Atlas Connection
DB_CONNECTION=mongodb
MONGO_URI=mongodb+srv://username:password@cluster.mongodb.net/kelurahan_pabuaran_mekar?retryWrites=true&w=majority
DB_DATABASE=kelurahan_pabuaran_mekar
```

**Cara mendapatkan Connection String:**
1. Login ke [MongoDB Atlas](https://cloud.mongodb.com/)
2. Pilih cluster Anda
3. Klik "Connect" → "Connect your application"
4. Copy connection string
5. Ganti `<password>` dengan password database user
6. Ganti `<database>` dengan nama database (opsional)

### 3. Verifikasi Connection

Jalankan Tinker untuk test connection:

```bash
php artisan tinker
```

Di Tinker, jalankan:

```php
use App\Models\PengajuanSurat;

// Test connection
try {
    $count = PengajuanSurat::count();
    echo "Connection OK! Total pengajuan: $count\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### 4. Insert Data Dummy (Testing)

```php
use App\Models\PengajuanSurat;
use App\Models\User;

// Ambil atau buat user
$user = User::first() ?? User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'role' => 'user',
]);

// Insert pengajuan dummy
$pengajuan = PengajuanSurat::create([
    'user_id' => $user->_id,
    'jenis_surat' => 'Surat Keterangan Kematian',
    'slug_layanan' => 'kematian',
    'no_pengajuan' => PengajuanSurat::generateNoPengajuan(),
    'data_pelapor' => [
        'nama_pelapor' => 'Budi Santoso',
        'nik_pelapor' => '3201010101010001',
        'alamat_lengkap' => 'Jl. Test No. 123',
        'nomor_telepon' => '081234567890',
        'hubungan_dengan_pembuat_surat' => 'Diri Sendiri',
    ],
    'status' => 'menunggu',
    'tanggal_pengajuan' => now(),
    'memerlukan_etiket' => true,
    'nomor_tiket' => PengajuanSurat::generateNomorTiket(),
    'status_tiket' => 'Menunggu Verifikasi',
    'created_by' => $user->_id,
]);

echo "Pengajuan berhasil dibuat! ID: " . $pengajuan->_id . "\n";
```

---

## ✅ Checklist Setup

- [ ] Package `mongodb/laravel-mongodb` sudah terinstall
- [ ] File `.env` sudah dikonfigurasi dengan `MONGO_URI`
- [ ] Connection test berhasil di Tinker
- [ ] Data dummy berhasil di-insert
- [ ] Collection `pengajuan_surat` sudah dibuat di MongoDB Atlas

---

## 🔧 Troubleshooting

### Error: "DSN or host required"

**Solusi:** Pastikan `MONGO_URI` di `.env` sudah benar dan tidak ada spasi.

```env
# ✅ BENAR
MONGO_URI=mongodb+srv://user:pass@cluster.mongodb.net/db?retryWrites=true&w=majority

# ❌ SALAH (ada spasi)
MONGO_URI= mongodb+srv://...
```

### Error: "Authentication failed"

**Solusi:** 
1. Pastikan username dan password di connection string benar
2. Pastikan database user memiliki akses ke database yang dituju
3. Cek IP whitelist di MongoDB Atlas (Network Access)

### Error: "Collection not found"

**Solusi:** 
- **TIDAK PERLU khawatir!** MongoDB akan otomatis membuat collection saat insert pertama kali.
- Pastikan insert data berhasil, collection akan dibuat otomatis.

### Error: "Class 'MongoDB\Laravel\Eloquent\Model' not found"

**Solusi:**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

---

## 📚 Dokumentasi Lengkap

- **Desain Database:** Lihat `MONGODB_DATABASE_DESIGN.md`
- **Contoh Kode:** Lihat `MONGODB_EXAMPLES.md`
- **Model:** Lihat `app/Models/PengajuanSurat.php`

---

## 🎯 Next Steps

1. ✅ Setup connection MongoDB Atlas
2. ✅ Test connection dengan Tinker
3. ✅ Insert data dummy untuk testing
4. ⏭️ Buat index di MongoDB untuk performa optimal
5. ⏭️ Implementasi CRUD operations di Controller
6. ⏭️ Setup validation rules
7. ⏭️ Implementasi notification system

---

## 📝 Catatan Penting

1. **Tidak Ada Migration**: MongoDB tidak memerlukan migration seperti SQL. Collection dibuat otomatis saat insert pertama.

2. **Flexible Schema**: MongoDB mendukung schema yang fleksibel. Field bisa berbeda antar document.

3. **ObjectId**: MongoDB menggunakan `ObjectId` untuk `_id`. Laravel otomatis handle konversi.

4. **Timestamps**: Laravel otomatis menambahkan `created_at` dan `updated_at` jika model menggunakan `timestamps`.

---

## 🔗 Link Berguna

- [MongoDB Laravel Package](https://github.com/mongodb/laravel-mongodb)
- [MongoDB Atlas Documentation](https://www.mongodb.com/docs/atlas/)
- [Laravel MongoDB Eloquent](https://github.com/mongodb/laravel-mongodb/blob/master/README.md)


# ✅ PERBAIKAN ALUR REVIEW PENGAJUAN SURAT - ADMIN

## 🎯 Masalah yang Diperbaiki

1. ✅ **Tombol Proses belum berfungsi** → Diperbaiki
2. ✅ **Tombol Dokumen belum berfungsi** → Diperbaiki
3. ✅ **Tombol Riwayat belum berfungsi** → Diperbaiki
4. ✅ **Tombol Tolak belum berfungsi** → Diperbaiki
5. ✅ **Tombol Revisi belum berfungsi** → Diperbaiki
6. ✅ **Approve belum generate nomor antrian** → Diperbaiki
7. ✅ **Tidak ada riwayat pengajuan** → Diperbaiki
8. ✅ **Query tidak filter status menunggu & diproses** → Diperbaiki

---

## 🔧 Perbaikan yang Dilakukan

### 1. **Model: `app/Models/PengajuanHistory.php`** (BARU)

Model untuk menyimpan riwayat perubahan status pengajuan.

**Field:**
- `pengajuan_id` - ID pengajuan
- `admin_id` - ID admin yang melakukan aksi
- `status_lama` - Status sebelum perubahan
- `status_baru` - Status setelah perubahan
- `catatan` - Catatan admin
- `action` - Jenis aksi (process, approve, reject, revise)
- `created_at` - Waktu perubahan

**Method:**
- `createHistory()` - Helper untuk membuat riwayat

### 2. **Model: `app/Models/PengajuanSurat.php`** (UPDATE)

**Field Tambahan:**
- `processed_at` - Waktu pengajuan diproses
- `admin_id` - ID admin yang memproses

**Relationship:**
- `history()` - Has many PengajuanHistory

### 3. **Controller: `app/Http/Controllers/AdminPengajuanController.php`**

#### ✅ Method `index()` - UPDATE
- **Filter default**: Menampilkan status `diajukan` (menunggu) dan `diproses`
- **Search**: Mencari berdasarkan nomor pengajuan, etiket, nama, jenis layanan, atau NIK
- **Statistics**: Total, menunggu, diproses, disetujui, ditolak

#### ✅ Method `process()` - BARU
- **Fungsi**: Update status dari `diajukan` (menunggu) → `diproses`
- **Validasi**: Hanya bisa proses jika status masih `diajukan`
- **Aksi**:
  - Update status menjadi `diproses`
  - Simpan `processed_at` dan `admin_id`
  - Simpan riwayat ke `PengajuanHistory`
  - Kirim notifikasi ke user

#### ✅ Method `showDocuments()` - BARU
- **Fungsi**: Menampilkan dokumen pengajuan
- **Validasi**: Hanya bisa lihat dokumen jika status sudah `diproses`
- **Return**: View dengan daftar dokumen

#### ✅ Method `showHistory()` - BARU
- **Fungsi**: Menampilkan riwayat perubahan status pengajuan
- **Return**: View dengan daftar riwayat

#### ✅ Method `approve()` - UPDATE
- **Fungsi**: Update status dari `diproses` → `disetujui`
- **Validasi**: Hanya bisa approve jika status sudah `diproses`
- **Aksi**:
  - Generate nomor antrian / e-ticket jika belum ada
  - Update status menjadi `disetujui`
  - Simpan riwayat ke `PengajuanHistory`
  - Kirim notifikasi ke user dengan nomor antrian

#### ✅ Method `reject()` - UPDATE
- **Fungsi**: Update status menjadi `ditolak`
- **Validasi**: Wajib isi alasan penolakan
- **Aksi**:
  - Update status menjadi `ditolak`
  - Simpan catatan admin (alasan penolakan)
  - Simpan riwayat ke `PengajuanHistory`
  - Kirim notifikasi ke user dengan alasan

#### ✅ Method `revise()` - BARU
- **Fungsi**: Kembalikan pengajuan untuk revisi
- **Validasi**: Wajib isi catatan perbaikan
- **Aksi**:
  - Update status kembali ke `diajukan` (menunggu)
  - Reset `processed_at` dan `admin_id`
  - Simpan catatan perbaikan
  - Simpan riwayat ke `PengajuanHistory`
  - Kirim notifikasi ke user dengan catatan perbaikan

### 4. **Routes: `routes/web.php`** (UPDATE)

**Routes Baru:**
```php
Route::post('/pengajuan/{id}/process', [AdminPengajuanController::class, 'process'])
    ->name('pengajuan.process');
Route::get('/pengajuan/{id}/documents', [AdminPengajuanController::class, 'showDocuments'])
    ->name('pengajuan.documents');
Route::get('/pengajuan/{id}/history', [AdminPengajuanController::class, 'showHistory'])
    ->name('pengajuan.history');
Route::post('/pengajuan/{id}/revise', [AdminPengajuanController::class, 'revise'])
    ->name('pengajuan.revise');
```

---

## 🔄 Alur Review Admin

### **1. Admin Klik Tombol "Proses":**

1. **Validasi**: Status harus `diajukan` (menunggu)
2. **Update**:
   - Status: `diajukan` → `diproses`
   - `processed_at`: Waktu sekarang
   - `admin_id`: ID admin yang memproses
3. **Riwayat**: Simpan ke `PengajuanHistory`
4. **Notifikasi**: Kirim ke user
5. **Result**: Pengajuan sekarang status `diproses`

### **2. Admin Klik Tombol "Dokumen":**

1. **Validasi**: Status harus `diproses`
2. **Tampilkan**: Daftar dokumen pengajuan
3. **Result**: Admin bisa review dokumen

### **3. Admin Klik Tombol "Approve":**

1. **Validasi**: Status harus `diproses`
2. **Generate**: Nomor antrian / e-ticket (jika belum ada)
3. **Update**:
   - Status: `diproses` → `disetujui`
   - `etiket`: Nomor antrian / e-ticket
4. **Riwayat**: Simpan ke `PengajuanHistory`
5. **Notifikasi**: Kirim ke user dengan nomor antrian
6. **Result**: Pengajuan selesai, user mendapat e-ticket

### **4. Admin Klik Tombol "Tolak":**

1. **Validasi**: Wajib isi alasan penolakan
2. **Update**:
   - Status: → `ditolak`
   - `catatan_admin`: Alasan penolakan
3. **Riwayat**: Simpan ke `PengajuanHistory`
4. **Notifikasi**: Kirim ke user dengan alasan
5. **Result**: Pengajuan ditolak

### **5. Admin Klik Tombol "Revisi":**

1. **Validasi**: Wajib isi catatan perbaikan
2. **Update**:
   - Status: → `diajukan` (kembali ke menunggu)
   - `catatan_admin`: Catatan perbaikan
   - `processed_at`: Reset ke null
   - `admin_id`: Reset ke null
3. **Riwayat**: Simpan ke `PengajuanHistory`
4. **Notifikasi**: Kirim ke user dengan catatan perbaikan
5. **Result**: Pengajuan dikembalikan untuk revisi

### **6. Admin Klik Tombol "Riwayat":**

1. **Tampilkan**: Daftar riwayat perubahan status
2. **Info**: Status lama, status baru, admin, waktu, catatan

---

## 📋 Struktur Database MongoDB

### Collection: `pengajuan_surat` (UPDATE)
```javascript
{
  "_id": ObjectId("..."),
  "user_id": ObjectId("..."),
  "nomor_pengajuan": "KET-1769074316533",
  "jenis_layanan": "Surat Keterangan Kelahiran",
  "status": "diproses", // diajukan | diproses | disetujui | ditolak
  "etiket": "KPM-2026-0001",
  "processed_at": ISODate("2026-01-22T10:00:00Z"), // ✅ Baru
  "admin_id": ObjectId("..."), // ✅ Baru
  "catatan_admin": "...",
  "created_at": ISODate("2026-01-22T09:00:00Z"),
  "updated_at": ISODate("2026-01-22T10:00:00Z")
}
```

### Collection: `pengajuan_history` (BARU)
```javascript
{
  "_id": ObjectId("..."),
  "pengajuan_id": ObjectId("..."),
  "admin_id": ObjectId("..."),
  "status_lama": "diajukan",
  "status_baru": "diproses",
  "action": "process", // process | approve | reject | revise
  "catatan": "Pengajuan diproses oleh admin",
  "created_at": ISODate("2026-01-22T10:00:00Z")
}
```

---

## ✅ Checklist Final

- [x] Model PengajuanHistory dibuat
- [x] Field `processed_at` dan `admin_id` ditambahkan ke PengajuanSurat
- [x] Method `process()` - update status menunggu → diproses
- [x] Method `showDocuments()` - lihat dokumen
- [x] Method `showHistory()` - lihat riwayat
- [x] Method `revise()` - status revisi
- [x] Method `approve()` - generate nomor antrian/e-ticket
- [x] Method `reject()` - simpan riwayat
- [x] Routes untuk semua aksi admin
- [x] Query `index()` filter status menunggu & diproses
- [x] Semua perubahan status disimpan ke riwayat
- [x] Notifikasi ke user setelah setiap perubahan

---

## 🚀 Testing

### Test Tombol Proses:
1. Login sebagai admin
2. Buka `/admin/pengajuan`
3. Klik tombol "Proses" pada pengajuan dengan status "Menunggu"
4. **Hasil yang diharapkan**:
   - ✅ Status berubah menjadi "Diproses"
   - ✅ `processed_at` tersimpan
   - ✅ `admin_id` tersimpan
   - ✅ Riwayat tersimpan
   - ✅ User mendapat notifikasi

### Test Tombol Approve:
1. Klik tombol "Approve" pada pengajuan dengan status "Diproses"
2. **Hasil yang diharapkan**:
   - ✅ Status berubah menjadi "Disetujui"
   - ✅ Nomor antrian / e-ticket di-generate
   - ✅ Riwayat tersimpan
   - ✅ User mendapat notifikasi dengan nomor antrian

### Test Tombol Tolak:
1. Klik tombol "Tolak" pada pengajuan
2. Isi alasan penolakan
3. **Hasil yang diharapkan**:
   - ✅ Status berubah menjadi "Ditolak"
   - ✅ Alasan tersimpan
   - ✅ Riwayat tersimpan
   - ✅ User mendapat notifikasi dengan alasan

### Test Tombol Revisi:
1. Klik tombol "Revisi" pada pengajuan
2. Isi catatan perbaikan
3. **Hasil yang diharapkan**:
   - ✅ Status kembali ke "Diajukan" (menunggu)
   - ✅ Catatan perbaikan tersimpan
   - ✅ `processed_at` dan `admin_id` di-reset
   - ✅ Riwayat tersimpan
   - ✅ User mendapat notifikasi dengan catatan

### Test Tombol Riwayat:
1. Klik tombol "Riwayat" pada pengajuan
2. **Hasil yang diharapkan**:
   - ✅ Daftar riwayat perubahan status ditampilkan
   - ✅ Menampilkan status lama, status baru, admin, waktu, catatan

---

## 📝 Catatan Penting

1. **Status Mapping**:
   - `diajukan` = Menunggu
   - `diproses` = Diproses
   - `disetujui` = Selesai
   - `ditolak` = Ditolak

2. **Workflow Status**:
   - `diajukan` → (Proses) → `diproses` → (Approve) → `disetujui`
   - `diajukan` → (Proses) → `diproses` → (Tolak) → `ditolak`
   - `diajukan` → (Proses) → `diproses` → (Revisi) → `diajukan`

3. **Validasi Aksi**:
   - Proses: Hanya bisa jika status `diajukan`
   - Dokumen: Hanya bisa jika status `diproses`
   - Approve: Hanya bisa jika status `diproses`
   - Tolak: Bisa kapan saja (wajib alasan)
   - Revisi: Bisa kapan saja (wajib catatan)

4. **Riwayat**: Semua perubahan status otomatis tersimpan ke `PengajuanHistory`

5. **Notifikasi**: User mendapat notifikasi setelah setiap perubahan status

---

**SEMUA PERBAIKAN SUDAH SELESAI!** 🎉

Sistem sekarang:
- ✅ Tombol Proses berfungsi (menunggu → diproses)
- ✅ Tombol Dokumen berfungsi (lihat dokumen)
- ✅ Tombol Riwayat berfungsi (lihat riwayat)
- ✅ Tombol Approve berfungsi (generate nomor antrian)
- ✅ Tombol Tolak berfungsi (dengan alasan)
- ✅ Tombol Revisi berfungsi (dengan catatan)
- ✅ Semua perubahan tersimpan ke riwayat
- ✅ Notifikasi ke user setelah setiap perubahan
- ✅ Query filter status menunggu & diproses


# Backend Flow Pengajuan Surat - LENGKAP & SIAP PAKAI

## ✅ File yang Sudah Diperbaiki

### 1. Model: `app/Models/PengajuanSurat.php`
✅ Field lengkap sesuai requirement
✅ Status: diajukan, diproses, disetujui, ditolak
✅ Generate etiket otomatis (KPM-YYYY-XXXX)
✅ Relationship dengan User dan Notification

### 2. Model: `app/Models/Notification.php`
✅ Field: user_id, pengajuan_id, message, is_read
✅ Relationship dengan User dan PengajuanSurat

### 3. Controller: `app/Http/Controllers/PengajuanController.php`
✅ Method `store()` - Simpan pengajuan dengan:
   - Validasi form
   - Generate nomor pengajuan & etiket
   - Simpan ke MongoDB (status: diajukan)
   - Simpan notifikasi ke database
   - Redirect dengan session flash & modal sukses
   - Error handling lengkap

### 4. Controller: `app/Http/Controllers/PengajuanSuratController.php`
✅ Method `status()` - Tampilkan status pengajuan user
✅ Method `store()` - Alternatif store dengan validasi

### 5. Controller: `app/Http/Controllers/AdminPengajuanController.php`
✅ Method `index()` - List semua pengajuan
✅ Method `approve()` - Approve pengajuan + notifikasi
✅ Method `reject()` - Reject pengajuan + notifikasi

### 6. Routes: `routes/web.php`
✅ User routes:
   - POST /pengajuan/{jenis} → store
   - GET /status → status pengajuan
✅ Admin routes:
   - GET /admin/pengajuan → index
   - POST /admin/pengajuan/{id}/approve → approve
   - POST /admin/pengajuan/{id}/reject → reject

### 7. View: `resources/views/pengajuan/status.blade.php`
✅ Notifikasi sukses di bagian atas
✅ List pengajuan dengan:
   - Jenis layanan
   - Etiket / nomor antrian
   - Status dengan badge warna
   - Tanggal pengajuan
✅ Modal sukses sesuai gambar
✅ Empty state jika belum ada pengajuan

---

## 🔄 Alur Sistem (FLOW)

### User Submit Pengajuan:
1. User isi form → Submit
2. `PengajuanController@store()`:
   - Validasi form ✅
   - Generate nomor pengajuan ✅
   - Generate etiket (KPM-2026-0001) ✅
   - Simpan ke MongoDB dengan status "diajukan" ✅
   - Simpan notifikasi ke database ✅
   - Redirect ke `/status` dengan:
     - Session flash success ✅
     - Session data untuk modal sukses ✅
3. Halaman Status:
   - Modal sukses muncul otomatis ✅
   - Data pengajuan ditampilkan ✅
   - Etiket ditampilkan ✅

### Admin Approve/Reject:
1. Admin lihat list di `/admin/pengajuan`
2. Admin approve/reject:
   - Update status pengajuan ✅
   - Simpan notifikasi ke user ✅
3. User mendapat notifikasi di database ✅

---

## 📋 Struktur Database MongoDB

### Collection: `pengajuan_surat`
```javascript
{
  "_id": ObjectId("..."),
  "user_id": ObjectId("..."),
  "jenis_layanan": "Surat Keterangan Kelahiran",
  "nama": "Budi Santoso",
  "nik": "3201010101010001",
  "alamat": "Jl. Test No. 123",
  "no_hp": "081234567890",
  "dokumen": [
    {"name": "file1.pdf", "path": "storage/pengajuan/..."}
  ],
  "status": "diajukan", // diajukan | diproses | disetujui | ditolak
  "etiket": "KPM-2026-0001",
  "catatan_admin": null,
  "created_at": ISODate("2026-01-01T10:00:00Z"),
  "updated_at": ISODate("2026-01-01T10:00:00Z")
}
```

### Collection: `notifications`
```javascript
{
  "_id": ObjectId("..."),
  "user_id": ObjectId("..."),
  "pengajuan_id": ObjectId("..."),
  "message": "Pengajuan Surat Keterangan Kelahiran berhasil diajukan. Etiket: KPM-2026-0001",
  "is_read": false,
  "created_at": ISODate("2026-01-01T10:00:00Z"),
  "updated_at": ISODate("2026-01-01T10:00:00Z")
}
```

---

## 🎯 Fitur yang Sudah Diimplementasikan

✅ **Notifikasi Sukses** - Setelah submit berhasil
✅ **Modal Sukses** - Sesuai desain gambar dengan:
   - Nomor pengajuan
   - Jenis surat & estimasi
   - Informasi download
   - Langkah selanjutnya
   - Cek status di dashboard
   - Notifikasi email
   - Butuh bantuan
   - Tombol "Tutup & Ke Dashboard"

✅ **Status Pengajuan** - Menampilkan:
   - Jenis layanan
   - Etiket / nomor antrian
   - Status (diajukan/diproses/disetujui/ditolak)
   - Tanggal pengajuan
   - Detail pemohon

✅ **Notifikasi Database** - Disimpan setelah:
   - Pengajuan berhasil
   - Admin approve/reject

✅ **Error Handling** - Try-catch untuk semua operasi database

---

## 🚀 Testing

1. **Test Submit Pengajuan:**
   - Login sebagai user
   - Isi form pengajuan
   - Submit
   - Harus muncul modal sukses
   - Harus redirect ke halaman status
   - Data harus muncul di list

2. **Test Status Pengajuan:**
   - Buka `/status`
   - Harus menampilkan semua pengajuan user
   - Harus menampilkan etiket
   - Harus menampilkan status dengan badge warna

3. **Test Admin:**
   - Login sebagai admin
   - Buka `/admin/pengajuan`
   - Approve/reject pengajuan
   - User harus mendapat notifikasi

---

## ✅ Checklist Final

- [x] Model PengajuanSurat lengkap
- [x] Model Notification lengkap
- [x] Controller store dengan error handling
- [x] Controller status mengambil data user
- [x] Admin controller approve/reject
- [x] Routes benar dan aktif
- [x] View status menampilkan data
- [x] Modal sukses sesuai gambar
- [x] Notifikasi tersimpan ke database
- [x] Etiket otomatis generate
- [x] Status default "diajukan"
- [x] Empty state jika data kosong

**SEMUA SUDAH SIAP PAKAI!** 🎉


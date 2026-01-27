# DOKUMENTASI SISTEM AUTENTIKASI & DASHBOARD
## Website Layanan Kelurahan Pabuaran Mekar

---

## ✅ PERUBAHAN YANG TELAH DILAKUKAN

### 1. **ROUTING & MIDDLEWARE** ✓
- ✅ Route `/login` dan `/register` menggunakan middleware `guest`
- ✅ User yang sudah login akan otomatis redirect ke `/dashboard` jika akses `/login` atau `/register`
- ✅ Semua route yang memerlukan autentikasi sudah menggunakan middleware `auth`
- ✅ Route terstruktur dengan grup untuk: Public, Auth, User, Admin

**File:** `routes/web.php`

---

### 2. **NAVBAR DINAMIS** ✓
Navbar sekarang menampilkan menu berbeda berdasarkan status login:

#### **User BELUM LOGIN:**
- Beranda
- Layanan
- Kontak
- Masuk (Button)
- Daftar (Button)

#### **User SUDAH LOGIN:**
- Dashboard
- Pengajuan Surat
- Status Pengajuan
- Dropdown Akun:
  - Profil Saya
  - Logout

**File:** `views/components/navbar.blade.php`, `views/partials/navbar.blade.php`

---

### 3. **MODEL PENGAJUAN SURAT** ✓
Dibuat model MongoDB untuk menyimpan data pengajuan:

```php
Collection: pengajuan_surat

Fields:
- user_id
- jenis_surat
- no_pengajuan (auto-generated: PJ-20260108-0001)
- data_form (array: nama, nik, alamat, no_hp, keterangan)
- file_upload (array: path ke file)
- status (menunggu, diproses, selesai, ditolak)
- tanggal_pengajuan
- estimasi_selesai
- keterangan
- nomor_surat
- tiket_code
```

**File:** `app/Models/PengajuanSurat.php`

---

### 4. **DASHBOARD USER** ✓
Dashboard menampilkan:

#### **Statistik Cards:**
- Total Pengajuan
- Menunggu (Orange)
- Diproses (Blue)
- Selesai (Green)

#### **Tombol:**
- Ajukan Surat Baru (Yellow Button)

#### **Tabel Riwayat Pengajuan:**
- Nomor Pengajuan
- Nama Surat
- Tanggal Pengajuan
- Estimasi Selesai
- Status (Badge warna)
- Keterangan

**File:** `views/dashboard.blade.php`, `app/Http/Controllers/DashboardController.php`

---

### 5. **5 LAYANAN SURAT** ✓
Sistem hanya menyediakan 5 layanan:

1. **Surat Keterangan Kelahiran** (`/pengajuan/kelahiran`)
2. **Surat Pernyataan Waris** (`/pengajuan/waris`)
3. **Surat Keterangan Usaha** (`/pengajuan/usaha`)
4. **Surat Keterangan Domisili Usaha** (`/pengajuan/domisili-usaha`)
5. **Pengantar PBB** (`/pengajuan/pbb`)

Setiap layanan memiliki:
- Nama
- Deskripsi
- Icon
- Daftar Persyaratan
- Form Pengajuan

**File:** `app/Http/Controllers/PengajuanController.php`

---

### 6. **HALAMAN PENGAJUAN** ✓

#### **`/pengajuan` - Daftar Layanan:**
- Card untuk setiap layanan
- Menampilkan persyaratan
- Button "Isi Form Pengajuan"

#### **`/pengajuan/{jenis}` - Form:**
- Form input: Nama, NIK, Alamat, No HP, Keterangan
- Upload file (PDF, JPG, PNG - max 2MB)
- Auto-fill data dari profil user
- Validasi form
- Simpan ke MongoDB

**File:** 
- `views/pengajuan/index.blade.php`
- `views/pengajuan/form.blade.php`

---

### 7. **HALAMAN STATUS PENGAJUAN** ✓
**`/status`**

Menampilkan:
- List semua pengajuan user
- Status badge (warna berbeda)
- Detail pengajuan
- Tanggal & estimasi selesai
- Button download (jika selesai)
- Pagination

**File:** `views/pengajuan/status.blade.php`

---

### 8. **HALAMAN PROFIL** ✓
**`/profil`**

Menampilkan data user (Read-only):
- Nama Lengkap
- Email
- NIK/NIP
- No. HP
- Alamat
- Role

**File:** `views/profil/index.blade.php`, `app/Http/Controllers/ProfilController.php`

---

## 📁 STRUKTUR FILE BARU

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php (✓ Updated)
│   │   ├── DashboardController.php (✓ Updated)
│   │   ├── PengajuanController.php (✓ New)
│   │   └── ProfilController.php (✓ New)
│   └── Middleware/
│       ├── AdminMiddleware.php (✓ Existing)
│       └── AuthenticateOrAdmin.php (✓ Existing)
└── Models/
    └── PengajuanSurat.php (✓ New)

routes/
└── web.php (✓ Updated)

views/
├── components/
│   └── navbar.blade.php (✓ Updated)
├── partials/
│   └── navbar.blade.php (✓ New)
├── pengajuan/
│   ├── index.blade.php (✓ New)
│   ├── form.blade.php (✓ New)
│   └── status.blade.php (✓ New)
├── profil/
│   └── index.blade.php (✓ New)
└── dashboard.blade.php (✓ Updated)

storage/app/public/
└── pengajuan/ (✓ New directory)
```

---

## 🔄 ALUR KERJA SISTEM

### **Alur User Baru:**
1. Buka landing page
2. Klik "Daftar" → Form registrasi
3. Setelah registrasi → Auto login → Redirect ke `/dashboard`

### **Alur Login:**
1. Klik "Masuk" → Form login
2. Login berhasil → Redirect ke `/dashboard`
3. Navbar berubah (tidak ada Login/Daftar)

### **Alur Pengajuan Surat:**
1. Dari dashboard → Klik "Ajukan Surat Baru"
2. Pilih jenis surat
3. Isi form (data auto-fill dari profil)
4. Upload berkas
5. Submit → Redirect ke dashboard
6. Lihat status di "Status Pengajuan"

### **Alur Logout:**
1. Klik dropdown akun → Logout
2. Session dihapus
3. Redirect ke `/login`

---

## 🛠️ CARA TESTING

### **1. Test Registrasi:**
```
1. Akses: http://localhost:8000/register
2. Isi form registrasi
3. Submit → Harus redirect ke /dashboard
4. Cek navbar → Harus tidak ada "Login" & "Daftar"
```

### **2. Test Login:**
```
1. Logout dulu (jika sudah login)
2. Akses: http://localhost:8000/login
3. Login → Harus redirect ke /dashboard
4. Cek navbar → Harus tampil "Dashboard", "Pengajuan", dll
```

### **3. Test Redirect jika Sudah Login:**
```
1. Login terlebih dahulu
2. Akses manual: http://localhost:8000/login
3. Harus otomatis redirect ke /dashboard
4. Test juga: http://localhost:8000/register
```

### **4. Test Pengajuan Surat:**
```
1. Login sebagai user
2. Dashboard → Klik "Ajukan Surat Baru"
3. Pilih "Surat Keterangan Kelahiran"
4. Isi form & upload file
5. Submit → Cek dashboard (statistik harus bertambah)
6. Cek "Status Pengajuan" → Harus muncul pengajuan baru
```

### **5. Test Profil:**
```
1. Navbar → Dropdown Akun → Profil Saya
2. Cek data tampil lengkap (read-only)
```

---

## 🎨 UI/UX HIGHLIGHTS

- ✅ **Statistik Cards** dengan icon & warna berbeda
- ✅ **Badge Status** dinamis (Orange/Blue/Green)
- ✅ **Responsive Design** (Mobile & Desktop)
- ✅ **Hover Effects** pada cards & buttons
- ✅ **Empty State** untuk pengajuan kosong
- ✅ **Form Validation** dengan error messages
- ✅ **Success Messages** setelah submit
- ✅ **Dropdown Menu** untuk akun user

---

## 📝 CATATAN PENTING

1. **MongoDB Connection:** Pastikan MongoDB berjalan dan terkonfigurasi di `.env`
2. **Storage Link:** Jalankan `php artisan storage:link` jika belum
3. **File Upload:** Direktori `storage/app/public/pengajuan` sudah dibuat
4. **Admin Login:** Masih menggunakan `.env` (ADMIN_EMAIL & ADMIN_PASSWORD)
5. **User Registration:** Hanya role "user" yang bisa daftar

---

## 🚀 NEXT STEPS (Opsional)

1. Implementasi download surat untuk status "selesai"
2. Tambahkan notifikasi real-time
3. Email notification untuk perubahan status
4. Export PDF untuk pengajuan
5. History log untuk setiap pengajuan

---

**Dibuat pada:** 8 Januari 2026  
**Status:** ✅ SELESAI SEMUA TODO


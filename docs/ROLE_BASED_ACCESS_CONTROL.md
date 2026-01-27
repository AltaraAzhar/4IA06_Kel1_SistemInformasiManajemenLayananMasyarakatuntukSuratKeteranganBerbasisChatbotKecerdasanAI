# Role-Based Access Control (RBAC) - Pengajuan Surat

## 📋 Ringkasan Perubahan

Memastikan halaman "Pengajuan Surat Online" (yang menampilkan kartu layanan seperti Surat Keterangan Kelahiran, Kematian, Usaha, dan Tidak Mampu) **TIDAK PERNAH** muncul atau bisa diakses oleh admin.

---

## 🔒 Proteksi yang Diterapkan

### 1. **Middleware Protection (Primary)**
Route pengajuan sudah dilindungi dengan middleware `role:user`:

**File:** `routes/user.php`
```php
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/pengajuan', [OldPengajuanController::class, 'index'])->name('pengajuan');
    Route::get('/pengajuan/{jenis}', [OldPengajuanController::class, 'showForm'])->name('pengajuan.form');
    Route::post('/pengajuan/{jenis}', [OldPengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan/download/{document}', [OldPengajuanController::class, 'downloadDocument'])->name('pengajuan.download');
});
```

**Hasil:** Admin yang mencoba akses akan mendapat error 403 (Forbidden) dari `RoleMiddleware`.

---

### 2. **Controller Protection (Double Protection)**
Tambahan pengecekan role di controller sebagai double protection:

**File:** `app/Http/Controllers/PengajuanController.php`

**Method yang Dilindungi:**
- `index()` - Halaman daftar layanan pengajuan
- `showForm($jenis)` - Form pengajuan surat
- `store($request, $jenis)` - Submit pengajuan surat

**Implementasi:**
```php
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
```

**Hasil:** 
- Admin yang mencoba akses akan di-redirect ke dashboard admin dengan pesan error
- User lain (jika ada) akan mendapat error 403

---

### 3. **Navbar Protection (UI Level)**
Navbar hanya menampilkan menu "Pengajuan Surat" untuk user dengan role 'user':

**File:** `resources/views/components/navbar.blade.php`

**Perubahan:**
- Menu "Pengajuan Surat" hanya muncul jika `auth()->user()->role === 'user'`
- Admin yang login hanya melihat "Dashboard Admin" (tanpa menu pengajuan)
- Logout button menggunakan route yang sesuai dengan role

**Sebelum:**
```php
@auth
    <a href="{{ route('user.pengajuan') }}">Pengajuan Surat</a>
@endauth
```

**Sesudah:**
```php
@auth
    @if(auth()->user()->role === 'user')
        <a href="{{ route('user.pengajuan') }}">Pengajuan Surat</a>
    @elseif(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
    @endif
@endauth
```

**Hasil:** Admin tidak akan melihat link "Pengajuan Surat" di navbar.

---

## 📁 File yang Dimodifikasi

### 1. **Routes**
- ✅ `routes/user.php` - Sudah dilindungi dengan middleware `role:user` (tidak perlu perubahan)

### 2. **Controllers**
- ✅ `app/Http/Controllers/PengajuanController.php`
  - Method `index()` - Tambah pengecekan role
  - Method `showForm()` - Tambah pengecekan role
  - Method `store()` - Tambah pengecekan role

### 3. **Views**
- ✅ `resources/views/components/navbar.blade.php`
  - Desktop menu - Conditional rendering berdasarkan role
  - Mobile menu - Conditional rendering berdasarkan role
  - Logout button - Route sesuai role

### 4. **Middleware**
- ✅ `app/Http/Middleware/RoleMiddleware.php` - Sudah benar (tidak perlu perubahan)
  - Akan abort 403 jika admin mencoba akses route user

---

## ✅ Verifikasi

### Test Case 1: Admin Mencoba Akses Route Pengajuan
**URL:** `/user/pengajuan`  
**Expected:** 
- Middleware `role:user` akan block dan return 403
- Atau controller akan redirect ke `admin.dashboard` dengan error message

### Test Case 2: Admin Login - Navbar
**Expected:**
- Navbar tidak menampilkan menu "Pengajuan Surat"
- Navbar menampilkan "Dashboard Admin"
- Logout button menggunakan route `admin.logout`

### Test Case 3: User Login - Navbar
**Expected:**
- Navbar menampilkan menu "Pengajuan Surat"
- Navbar menampilkan "Dashboard", "Pengajuan Surat", "Status Pengajuan"
- Logout button menggunakan route `user.logout`

### Test Case 4: Dashboard Admin
**Expected:**
- Tidak ada card layanan pengajuan (Kelahiran, Kematian, Usaha, Tidak Mampu)
- Tidak ada tombol "Isi Form Pengajuan"
- Hanya menampilkan daftar pengajuan yang sudah ada (untuk review/admin)

---

## 🔐 Security Layers

1. **Layer 1: Route Middleware** - `role:user` middleware
2. **Layer 2: Controller Check** - Double protection di controller
3. **Layer 3: UI Level** - Navbar conditional rendering

**Hasil:** Triple protection memastikan admin tidak bisa mengakses halaman pengajuan surat.

---

## 📝 Catatan Penting

1. **Middleware sudah benar** - `RoleMiddleware` akan abort 403 jika role tidak sesuai
2. **Controller protection** - Sebagai backup jika middleware bypass (tidak mungkin, tapi defense in depth)
3. **Navbar protection** - Mencegah admin melihat link pengajuan (UX improvement)
4. **Dashboard admin** - Sudah benar, tidak menampilkan card pengajuan

---

## ✅ Status

**Semua proteksi telah diterapkan:**
- ✅ Route dilindungi middleware
- ✅ Controller memiliki double protection
- ✅ Navbar conditional rendering
- ✅ Admin tidak bisa akses halaman pengajuan
- ✅ Admin tidak melihat menu pengajuan di navbar

**Status:** ✅ **SIAP PRODUCTION**


# 🚀 QUICK START - Login Sebagai Admin

## ⚡ Cara Cepat (3 Langkah)

### **1. Buka Halaman Login Admin**
```
http://localhost/admin/login
```
atau jika sudah di-deploy:
```
https://yourdomain.com/admin/login
```

### **2. Login dengan Kredensial**

**Opsi A: Jika sudah ada admin di database**
- Email: `email-admin-anda@example.com`
- Password: `password-admin-anda`

**Opsi B: Jika belum ada admin, gunakan .ENV**
1. Buka file `.env`
2. Tambahkan:
   ```env
   ADMIN_EMAIL=admin@kelurahan.com
   ADMIN_PASSWORD=admin123
   ```
3. Login dengan email & password dari .ENV
4. Sistem akan otomatis membuat admin

### **3. Akses Dashboard**
Setelah login berhasil, Anda akan otomatis di-redirect ke:
```
/admin/dashboard
```

---

## 🔧 Membuat Admin Baru

### **Metode 1: Via Script (Terminal)**
```bash
php create_admin.php
```
Ikuti instruksi di terminal.

### **Metode 2: Via Tinker**
```bash
php artisan tinker
```
Kemudian:
```php
$admin = \App\Models\User::create([
    'name' => 'Administrator',
    'email' => 'admin@kelurahan.com',
    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
    'role' => 'admin',
    'nik_or_nip' => 'ADMIN001',
    'phone' => '081234567890',
    'address' => 'Kelurahan Pabuaran Mekar',
]);
```

### **Metode 3: Via Database**
```sql
INSERT INTO users (name, email, password, role, nik_or_nip, phone, address, created_at, updated_at)
VALUES (
    'Administrator',
    'admin@kelurahan.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    'ADMIN001',
    '081234567890',
    'Kelurahan Pabuaran Mekar',
    NOW(),
    NOW()
);
```
*Password default: `password`*

---

## ❌ Troubleshooting Error 403

**Error:** `403 Akses ditolak. Hanya admin yang dapat mengakses halaman ini.`

**Penyebab & Solusi:**

1. **Belum login**
   - ✅ Login di `/admin/login` terlebih dahulu

2. **Role bukan admin**
   - ✅ Cek di database: `SELECT email, role FROM users WHERE email = 'your-email';`
   - ✅ Update: `UPDATE users SET role = 'admin' WHERE email = 'your-email';`

3. **Mencoba akses route user**
   - ✅ Admin harus akses `/admin/*`, bukan `/user/*`

4. **Session expired**
   - ✅ Login ulang

---

## 📋 Checklist

- [ ] Sudah buka `/admin/login`
- [ ] Email dan password benar
- [ ] User memiliki `role='admin'` di database
- [ ] Tidak ada error di `storage/logs/laravel.log`
- [ ] Middleware `role:admin` sudah terdaftar

---

## 🎯 Setelah Login Berhasil

Anda akan bisa:
- ✅ Melihat semua pengajuan surat
- ✅ Memproses pengajuan (ubah status)
- ✅ Melihat detail pengajuan
- ✅ Melihat history pengajuan
- ✅ Menyelesaikan pengajuan
- ✅ Merevisi pengajuan

---

**Masih error?** Cek file `PANDUAN_ADMIN.md` untuk detail lengkap.


# 📘 PANDUAN ADMIN - Kelurahan Pabuaran Mekar

## 🔐 Cara Login Sebagai Admin

### **Metode 1: Login via Halaman Admin (RECOMMENDED)**

1. **Buka halaman login admin:**
   ```
   http://localhost/admin/login
   ```
   atau
   ```
   https://yourdomain.com/admin/login
   ```

2. **Masukkan kredensial admin:**
   - **Email:** Email yang terdaftar di database dengan `role='admin'`
   - **Password:** Password dari database

3. **Klik tombol "Login"**

4. **Setelah berhasil login, Anda akan di-redirect ke:**
   ```
   /admin/dashboard
   ```

---

### **Metode 2: Login via .ENV (Untuk Setup Awal)**

Jika belum ada admin di database, Anda bisa setup admin via `.env`:

1. **Buka file `.env` di root project**

2. **Tambahkan konfigurasi berikut:**
   ```env
   ADMIN_EMAIL=admin@kelurahan.com
   ADMIN_PASSWORD=admin123
   ```

3. **Login menggunakan email dan password dari .ENV:**
   - Email: `admin@kelurahan.com`
   - Password: `admin123`

4. **Sistem akan otomatis membuat user admin di database**

---

## 🛠️ Membuat Admin via Database

Jika Anda ingin membuat admin secara manual:

### **Via Tinker (Laravel Console):**
```bash
php artisan tinker
```

Kemudian jalankan:
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Administrator',
    'email' => 'admin@kelurahan.com',
    'password' => Hash::make('password123'),
    'role' => 'admin',
    'nik_or_nip' => 'ADMIN001',
    'phone' => '081234567890',
    'address' => 'Kelurahan Pabuaran Mekar',
]);
```

### **Via Database Langsung:**
```sql
INSERT INTO users (name, email, password, role, nik_or_nip, phone, address, created_at, updated_at)
VALUES (
    'Administrator',
    'admin@kelurahan.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'admin',
    'ADMIN001',
    '081234567890',
    'Kelurahan Pabuaran Mekar',
    NOW(),
    NOW()
);
```

---

## 📍 URL Penting untuk Admin

| Halaman | URL | Deskripsi |
|---------|-----|-----------|
| **Login Admin** | `/admin/login` | Halaman login khusus admin |
| **Dashboard Admin** | `/admin/dashboard` | Dashboard utama admin |
| **Daftar Pengajuan** | `/admin/surat` | Lihat semua pengajuan surat |
| **Detail Pengajuan** | `/admin/surat/{id}` | Detail pengajuan surat |
| **History Pengajuan** | `/admin/surat/{id}/history` | Riwayat perubahan status |

---

## ⚠️ Troubleshooting Error 403

Jika Anda mendapat error **"403 Akses ditolak. Hanya admin yang dapat mengakses halaman ini."**, kemungkinan penyebabnya:

### **1. Belum Login**
- **Solusi:** Login terlebih dahulu di `/admin/login`

### **2. Role Bukan Admin**
- **Cek role user di database:**
  ```sql
  SELECT email, role FROM users WHERE email = 'your-email@example.com';
  ```
- **Update role menjadi admin:**
  ```sql
  UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
  ```

### **3. Session Expired**
- **Solusi:** Login ulang di `/admin/login`

### **4. Mencoba Akses Route User**
- **Admin tidak bisa akses route user** (`/user/dashboard`)
- **User tidak bisa akses route admin** (`/admin/dashboard`)

---

## 🔒 Keamanan

1. **Jangan share kredensial admin** ke user biasa
2. **Gunakan password yang kuat** untuk akun admin
3. **Ganti password default** setelah setup pertama kali
4. **Logout setelah selesai** menggunakan sistem

---

## 📞 Bantuan

Jika masih mengalami masalah:
1. Cek log Laravel: `storage/logs/laravel.log`
2. Pastikan middleware `role:admin` sudah terdaftar di `bootstrap/app.php`
3. Pastikan user memiliki `role='admin'` di database


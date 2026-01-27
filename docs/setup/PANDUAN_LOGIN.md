# 🔐 PANDUAN LOGIN ADMIN & USER

## 📋 Cara Login

### **1. LOGIN SEBAGAI ADMIN**

Admin login menggunakan kredensial yang disimpan di file `.env`.

#### **Langkah-langkah:**

1. **Buka file `.env`** di root project
2. **Pastikan ada konfigurasi berikut:**
   ```env
   ADMIN_EMAIL=admin@kelurahan.go.id
   ADMIN_PASSWORD=admin123
   ```
   *(Ganti dengan email dan password admin Anda)*

3. **Buka halaman login**: `http://localhost:8000/login` atau `http://your-domain.com/login`

4. **Masukkan kredensial admin:**
   - **Email**: Email yang sama dengan `ADMIN_EMAIL` di `.env`
   - **Password**: Password yang sama dengan `ADMIN_PASSWORD` di `.env`

5. **Klik "Masuk"**

6. **Hasil**: Anda akan di-redirect ke `/admin/dashboard`

#### **Catatan Penting:**
- ✅ Admin login **TIDAK** menggunakan database MongoDB
- ✅ Admin login menggunakan session (`session('admin')`)
- ✅ Email admin di `.env` **TIDAK BISA** digunakan untuk registrasi user
- ✅ Jika ingin mengubah kredensial admin, edit file `.env` dan restart server

---

### **2. LOGIN SEBAGAI USER**

User login menggunakan kredensial yang tersimpan di database MongoDB.

#### **Langkah-langkah:**

#### **A. Registrasi User Baru (Jika Belum Punya Akun):**

1. **Buka halaman registrasi**: `http://localhost:8000/register` atau `http://your-domain.com/register`

2. **Isi form registrasi:**
   - **Nama Lengkap**: Nama Anda
   - **Email**: Email Anda (harus unik, tidak boleh sama dengan ADMIN_EMAIL)
   - **NIK/NIP**: Nomor Induk Kependudukan atau Nomor Induk Pegawai
   - **Nomor Telepon**: Nomor telepon Anda
   - **Alamat**: Alamat lengkap (opsional)
   - **Password**: Password Anda
   - **Konfirmasi Password**: Ketik ulang password

3. **Klik "Daftar"**

4. **Hasil**: Anda akan di-redirect ke halaman login dengan pesan sukses

#### **B. Login User:**

1. **Buka halaman login**: `http://localhost:8000/login` atau `http://your-domain.com/login`

2. **Masukkan kredensial user:**
   - **Email**: Email yang Anda daftarkan saat registrasi
   - **Password**: Password yang Anda buat saat registrasi

3. **Klik "Masuk"**

4. **Hasil**: Anda akan di-redirect ke `/dashboard` (user dashboard)

#### **Catatan Penting:**
- ✅ User login menggunakan database MongoDB (collection `users`)
- ✅ User harus registrasi terlebih dahulu
- ✅ Role user otomatis diset ke `'user'` (tidak bisa daftar sebagai admin)
- ✅ Email yang sama dengan `ADMIN_EMAIL` tidak bisa digunakan untuk registrasi

---

## 🔧 Konfigurasi Awal

### **Setup Admin Login:**

1. **Buka file `.env`** di root project

2. **Tambahkan atau edit konfigurasi berikut:**
   ```env
   ADMIN_EMAIL=admin@kelurahan.go.id
   ADMIN_PASSWORD=admin123
   ```

3. **Simpan file `.env`**

4. **Restart server** (jika sedang running):
   ```bash
   php artisan serve
   ```

### **Setup User (Registrasi):**

User tidak perlu setup khusus, cukup registrasi melalui halaman `/register`.

---

## 🧪 Testing Login

### **Test Admin Login:**

1. Pastikan `.env` sudah dikonfigurasi dengan `ADMIN_EMAIL` dan `ADMIN_PASSWORD`
2. Buka `/login`
3. Masukkan email dan password dari `.env`
4. Klik "Masuk"
5. **Hasil yang diharapkan**: Redirect ke `/admin/dashboard`

### **Test User Login:**

1. Registrasi user baru di `/register`
2. Buka `/login`
3. Masukkan email dan password yang baru didaftarkan
4. Klik "Masuk"
5. **Hasil yang diharapkan**: Redirect ke `/dashboard`

---

## 🛠️ Membuat User Admin di Database (Alternatif)

Jika Anda ingin membuat user admin di database MongoDB (bukan via `.env`), gunakan Tinker:

```bash
php artisan tinker
```

Kemudian jalankan:

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin Kelurahan',
    'email' => 'admin@kelurahan.go.id',
    'nik_or_nip' => '1234567890123456',
    'phone' => '081234567890',
    'address' => 'Alamat Admin',
    'role' => 'admin',
    'password' => Hash::make('admin123'),
]);
```

**Catatan**: User dengan role `'admin'` di database juga bisa login sebagai admin jika middleware mendukung.

---

## 📝 Route Login

- **Halaman Login**: `GET /login` → `AuthController@showLoginForm`
- **Proses Login**: `POST /login` → `AuthController@login`
- **Halaman Registrasi**: `GET /register` → `AuthController@showRegisterForm`
- **Proses Registrasi**: `POST /register` → `AuthController@register`
- **Logout**: `POST /logout` → `AuthController@logout`

---

## 🔒 Middleware

### **Admin Middleware:**
- Cek session admin (`session('admin')`)
- Atau cek user dengan role `'admin'`

### **User Middleware:**
- Cek user sudah login
- Cek user dengan role `'user'`

---

## ⚠️ Troubleshooting

### **Admin tidak bisa login:**
1. Pastikan `.env` sudah dikonfigurasi dengan benar
2. Pastikan email dan password sesuai dengan yang di `.env`
3. Pastikan server sudah di-restart setelah edit `.env`
4. Cek file `.env` tidak ada spasi di awal/akhir nilai

### **User tidak bisa login:**
1. Pastikan user sudah registrasi
2. Pastikan email dan password benar
3. Pastikan email tidak sama dengan `ADMIN_EMAIL`
4. Cek database MongoDB collection `users` apakah user sudah terdaftar

### **User tidak bisa registrasi:**
1. Pastikan email belum terdaftar
2. Pastikan email tidak sama dengan `ADMIN_EMAIL`
3. Pastikan password dan konfirmasi password sama
4. Pastikan semua field wajib sudah diisi

---

## 📌 Contoh Kredensial Default

### **Admin (via .env):**
```
Email: admin@kelurahan.go.id
Password: admin123
```

### **User (via registrasi):**
```
Email: user@example.com
Password: password123
```

**⚠️ PENTING**: Ganti kredensial default dengan yang lebih aman untuk production!

---

## 🎯 Kesimpulan

- **Admin Login**: Menggunakan `.env` file (`ADMIN_EMAIL` dan `ADMIN_PASSWORD`)
- **User Login**: Menggunakan database MongoDB (harus registrasi dulu)
- **Pemisahan**: Admin dan user menggunakan sistem autentikasi terpisah
- **Security**: Admin tidak bisa didaftarkan melalui form registrasi


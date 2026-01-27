# 🔐 CARA LOGIN ADMIN & USER

## 📌 LOGIN SEBAGAI ADMIN

### **Cara 1: Menggunakan File .env (Recommended)**

1. **Buka file `.env`** di root project Anda
2. **Tambahkan atau edit baris berikut:**
   ```env
   ADMIN_EMAIL=admin@kelurahan.go.id
   ADMIN_PASSWORD=admin123
   ```
   *(Ganti dengan email dan password admin yang Anda inginkan)*

3. **Simpan file `.env`**

4. **Buka halaman login**: 
   - URL: `http://localhost:8000/login` (jika local)
   - Atau: `http://your-domain.com/login` (jika production)

5. **Masukkan kredensial:**
   - **Email**: `admin@kelurahan.go.id` (atau sesuai ADMIN_EMAIL di .env)
   - **Password**: `admin123` (atau sesuai ADMIN_PASSWORD di .env)

6. **Klik tombol "Masuk"**

7. **Hasil**: Anda akan di-redirect ke `/admin/dashboard`

---

### **Cara 2: Membuat User Admin di Database**

Jika Anda ingin membuat user admin di MongoDB:

1. **Buka terminal/command prompt**

2. **Jalankan Tinker:**
   ```bash
   php artisan tinker
   ```

3. **Jalankan perintah berikut:**
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

4. **Exit Tinker:**
   ```php
   exit
   ```

5. **Login menggunakan email dan password yang baru dibuat**

---

## 📌 LOGIN SEBAGAI USER

### **Langkah 1: Registrasi (Jika Belum Punya Akun)**

1. **Buka halaman registrasi**: 
   - URL: `http://localhost:8000/register`

2. **Isi form registrasi:**
   - **Nama Lengkap**: Contoh: `Budi Santoso`
   - **Email**: Contoh: `budi@example.com` (harus unik, tidak boleh sama dengan ADMIN_EMAIL)
   - **NIK/NIP**: Contoh: `3201010101010001`
   - **Nomor Telepon**: Contoh: `081234567890`
   - **Alamat**: Contoh: `Jl. Contoh No. 123` (opsional)
   - **Password**: Contoh: `password123`
   - **Konfirmasi Password**: Ketik ulang password yang sama

3. **Klik tombol "Daftar"**

4. **Hasil**: Anda akan di-redirect ke halaman login dengan pesan sukses

### **Langkah 2: Login User**

1. **Buka halaman login**: 
   - URL: `http://localhost:8000/login`

2. **Masukkan kredensial:**
   - **Email**: Email yang Anda daftarkan saat registrasi
   - **Password**: Password yang Anda buat saat registrasi

3. **Klik tombol "Masuk"**

4. **Hasil**: Anda akan di-redirect ke `/dashboard` (user dashboard)

---

## 🎯 Ringkasan Cepat

### **Admin Login:**
```
Email: admin@kelurahan.go.id (sesuai ADMIN_EMAIL di .env)
Password: admin123 (sesuai ADMIN_PASSWORD di .env)
→ Redirect ke: /admin/dashboard
```

### **User Login:**
```
1. Registrasi dulu di /register
2. Login dengan email & password yang didaftarkan
→ Redirect ke: /dashboard
```

---

## ⚙️ Setup Awal (Pertama Kali)

### **1. Setup Admin Login via .env:**

1. Buka file `.env` di root project
2. Tambahkan:
   ```env
   ADMIN_EMAIL=admin@kelurahan.go.id
   ADMIN_PASSWORD=admin123
   ```
3. Simpan file
4. Restart server (jika perlu)

### **2. Setup User (Tidak Perlu Setup Khusus):**

User cukup registrasi melalui halaman `/register`

---

## 🔍 Troubleshooting

### **❌ Admin tidak bisa login:**
- ✅ Pastikan `.env` sudah dikonfigurasi dengan benar
- ✅ Pastikan email dan password sesuai dengan yang di `.env`
- ✅ Pastikan tidak ada spasi di awal/akhir nilai di `.env`
- ✅ Restart server setelah edit `.env`

### **❌ User tidak bisa login:**
- ✅ Pastikan user sudah registrasi
- ✅ Pastikan email dan password benar
- ✅ Pastikan email tidak sama dengan `ADMIN_EMAIL`
- ✅ Cek database MongoDB collection `users`

### **❌ User tidak bisa registrasi:**
- ✅ Pastikan email belum terdaftar
- ✅ Pastikan email tidak sama dengan `ADMIN_EMAIL`
- ✅ Pastikan password dan konfirmasi password sama
- ✅ Pastikan semua field wajib sudah diisi

---

## 📝 Contoh Kredensial

### **Admin (Default):**
```
Email: admin@kelurahan.go.id
Password: admin123
```

### **User (Contoh):**
```
Email: user@example.com
Password: password123
```

**⚠️ PENTING**: Ganti kredensial default dengan yang lebih aman untuk production!

---

## 🚀 Quick Start

1. **Setup Admin**: Edit `.env` → tambahkan `ADMIN_EMAIL` dan `ADMIN_PASSWORD`
2. **Login Admin**: Buka `/login` → masukkan kredensial admin → akses `/admin/dashboard`
3. **Registrasi User**: Buka `/register` → isi form → daftar
4. **Login User**: Buka `/login` → masukkan kredensial user → akses `/dashboard`

---

**Selesai!** Sekarang Anda bisa login sebagai admin atau user. 🎉


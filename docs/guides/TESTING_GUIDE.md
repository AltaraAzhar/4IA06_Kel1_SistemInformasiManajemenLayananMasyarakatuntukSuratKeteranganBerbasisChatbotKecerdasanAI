# 🧪 PANDUAN TESTING
## Website Layanan Kelurahan Pabuaran Mekar

---

## 🚀 CARA MENJALANKAN APLIKASI

```bash
# 1. Pastikan MongoDB berjalan
# 2. Jalankan Laravel server
cd C:\Web_KelurahanPabuaranMekar
php artisan serve

# 3. Buka browser
# http://localhost:8000
```

---

## ✅ CHECKLIST TESTING

### **1. Test Halaman Landing (Guest)**
- [ ] Akses `http://localhost:8000`
- [ ] Navbar menampilkan: Beranda, Layanan, Kontak, **Masuk**, **Daftar**
- [ ] Hero section terlihat dengan baik
- [ ] Semua link berfungsi

---

### **2. Test Registrasi**
- [ ] Klik **Daftar** di navbar
- [ ] Isi form registrasi (gunakan email valid)
- [ ] Submit form
- [ ] **Expected:** Redirect ke `/dashboard`
- [ ] **Expected:** Navbar TIDAK menampilkan "Masuk" & "Daftar"
- [ ] **Expected:** Navbar menampilkan: Dashboard, Pengajuan Surat, Status Pengajuan, Dropdown Akun

---

### **3. Test Redirect untuk User Login**
Jika sudah login, test ini:

- [ ] Akses manual: `http://localhost:8000/login`
- [ ] **Expected:** Otomatis redirect ke `/dashboard`
- [ ] Akses manual: `http://localhost:8000/register`
- [ ] **Expected:** Otomatis redirect ke `/dashboard`

---

### **4. Test Dashboard User**
- [ ] Login sebagai user
- [ ] Dashboard menampilkan:
  - [ ] Welcome message dengan nama user
  - [ ] 4 Statistik cards (Total, Menunggu, Diproses, Selesai)
  - [ ] Button "Ajukan Surat Baru"
  - [ ] Tabel riwayat pengajuan (jika ada)
  - [ ] Empty state (jika belum ada pengajuan)

---

### **5. Test Pengajuan Surat - Halaman Index**
- [ ] Klik "Ajukan Surat Baru" dari dashboard
- [ ] **Expected:** Redirect ke `/pengajuan`
- [ ] Halaman menampilkan 5 card layanan:
  - [ ] Surat Keterangan Kelahiran
  - [ ] Surat Pernyataan Waris
  - [ ] Surat Keterangan Usaha
  - [ ] Surat Keterangan Domisili Usaha
  - [ ] Pengantar PBB
- [ ] Setiap card menampilkan:
  - [ ] Nama surat
  - [ ] Deskripsi
  - [ ] Icon
  - [ ] Daftar persyaratan
  - [ ] Button "Isi Form Pengajuan"

---

### **6. Test Form Pengajuan Surat**
Pilih salah satu layanan (misal: Surat Keterangan Kelahiran):

- [ ] Klik "Isi Form Pengajuan"
- [ ] **Expected:** Redirect ke `/pengajuan/kelahiran`
- [ ] Form menampilkan:
  - [ ] Nama Lengkap (auto-fill dari profil)
  - [ ] NIK (auto-fill)
  - [ ] Alamat (auto-fill)
  - [ ] No HP (auto-fill)
  - [ ] Keterangan (opsional)
  - [ ] Upload file (multiple)
- [ ] Isi form dan upload 1-2 file (PDF/JPG/PNG)
- [ ] Klik "Kirim Pengajuan"
- [ ] **Expected:** Redirect ke `/dashboard`
- [ ] **Expected:** Success message muncul
- [ ] **Expected:** Statistik "Total Pengajuan" bertambah
- [ ] **Expected:** Tabel riwayat menampilkan pengajuan baru

---

### **7. Test Status Pengajuan**
- [ ] Navbar → Klik "Status Pengajuan"
- [ ] **Expected:** Redirect ke `/status`
- [ ] Halaman menampilkan list pengajuan:
  - [ ] Nama surat
  - [ ] Nomor pengajuan (format: PJ-20260108-0001)
  - [ ] Status badge (Orange: Menunggu)
  - [ ] Tanggal pengajuan
  - [ ] Estimasi selesai
  - [ ] Data pemohon (Nama, NIK, No HP)
- [ ] Test pagination (jika ada banyak data)

---

### **8. Test Profil User**
- [ ] Navbar → Dropdown Akun → Klik "Profil Saya"
- [ ] **Expected:** Redirect ke `/profil`
- [ ] Halaman menampilkan:
  - [ ] Foto profil (icon)
  - [ ] Nama
  - [ ] Email
  - [ ] NIK/NIP
  - [ ] No HP
  - [ ] Alamat
  - [ ] Role (badge)
- [ ] Data sesuai dengan yang di-input saat registrasi
- [ ] Info box "read-only" muncul

---

### **9. Test Logout**
- [ ] Navbar → Dropdown Akun → Klik "Logout"
- [ ] **Expected:** Redirect ke `/login`
- [ ] **Expected:** Success message "Berhasil logout"
- [ ] **Expected:** Navbar kembali ke mode Guest (Masuk & Daftar muncul)

---

### **10. Test Login Setelah Logout**
- [ ] Klik "Masuk" di navbar
- [ ] Isi email & password yang tadi didaftarkan
- [ ] Klik "Login"
- [ ] **Expected:** Redirect ke `/dashboard`
- [ ] **Expected:** Navbar mode User (Dashboard, Pengajuan, dll)
- [ ] **Expected:** Data pengajuan sebelumnya masih ada

---

### **11. Test Responsive Design**
- [ ] Test di Mobile view (DevTools → Toggle device toolbar)
- [ ] Hamburger menu berfungsi
- [ ] Form tetap rapi
- [ ] Cards responsive
- [ ] Tabel scrollable

---

### **12. Test Validation**
#### **Form Registrasi:**
- [ ] Submit form kosong → Error validation muncul
- [ ] Email tidak valid → Error muncul
- [ ] Password kurang dari 8 karakter → Error muncul
- [ ] Password confirmation tidak cocok → Error muncul

#### **Form Pengajuan:**
- [ ] Submit form kosong → Error validation muncul
- [ ] NIK tidak 16 digit → Error muncul (jika ada validation)
- [ ] Upload file > 2MB → Error muncul
- [ ] Upload file format salah → Error muncul

---

## 🐛 EXPECTED ERRORS (Normal)

1. **"Belum ada pengajuan"** di dashboard → Normal untuk user baru
2. **Empty state** di Status Pengajuan → Normal jika belum ada pengajuan
3. **Auto-fill form kosong** → Terjadi jika user tidak isi alamat/phone saat register

---

## 📊 DATA TESTING

### **User Test Account:**
```
Email: test@example.com
Password: password123
Nama: User Test
NIK: 1234567890123456
Phone: 081234567890
Alamat: Jl. Test No. 123
```

### **Expected Database Records:**

#### **Collection: users**
```json
{
  "_id": "...",
  "name": "User Test",
  "email": "test@example.com",
  "nik_or_nip": "1234567890123456",
  "phone": "081234567890",
  "address": "Jl. Test No. 123",
  "role": "user",
  "password": "..." // hashed
}
```

#### **Collection: pengajuan_surat**
```json
{
  "_id": "...",
  "user_id": "...",
  "jenis_surat": "Surat Keterangan Kelahiran",
  "no_pengajuan": "PJ-20260108-0001",
  "data_form": {
    "nama_lengkap": "User Test",
    "nik": "1234567890123456",
    "alamat": "Jl. Test No. 123",
    "no_hp": "081234567890",
    "keterangan": "..."
  },
  "file_upload": ["pengajuan/..."],
  "status": "menunggu",
  "tanggal_pengajuan": "2026-01-08 ...",
  "estimasi_selesai": "2026-01-11 ...",
  "keterangan": "Pengajuan baru menunggu verifikasi"
}
```

---

## ✅ SUCCESS CRITERIA

Sistem dianggap berhasil jika:

1. ✅ User bisa registrasi dan otomatis login
2. ✅ Navbar berubah setelah login (tidak ada Masuk/Daftar)
3. ✅ User tidak bisa akses /login atau /register setelah login (redirect ke dashboard)
4. ✅ Dashboard menampilkan statistik yang benar
5. ✅ User bisa ajukan surat dan data tersimpan ke MongoDB
6. ✅ Status pengajuan menampilkan data yang benar
7. ✅ Profil user menampilkan data yang sesuai
8. ✅ Logout berhasil dan navbar kembali ke mode Guest

---

## 🔧 TROUBLESHOOTING

### **Problem: Navbar masih menampilkan "Masuk" & "Daftar" setelah login**
**Solution:**
- Clear cache: `php artisan optimize:clear`
- Refresh browser dengan `Ctrl + F5`

### **Problem: Error "Class PengajuanSurat not found"**
**Solution:**
- Run: `composer dump-autoload`

### **Problem: File upload error**
**Solution:**
- Pastikan folder `storage/app/public/pengajuan` ada
- Run: `php artisan storage:link`

### **Problem: Dashboard statistik tidak muncul**
**Solution:**
- Cek koneksi MongoDB di `.env`
- Restart server Laravel

---

**Selamat Testing! 🎉**


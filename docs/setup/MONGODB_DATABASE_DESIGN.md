# Desain Database MongoDB untuk Sistem Layanan Kelurahan

## 📋 Daftar Isi
1. [Struktur Collection](#struktur-collection)
2. [Struktur Document](#struktur-document)
3. [Jenis Layanan](#jenis-layanan)
4. [Status Pengajuan](#status-pengajuan)
5. [Konfigurasi MongoDB Atlas](#konfigurasi-mongodb-atlas)

---

## 🗂️ Struktur Collection

### Collection: `pengajuan_surat`

Collection utama untuk menyimpan semua pengajuan surat dari warga.

**Catatan Penting:**
- MongoDB **otomatis membuat collection** saat insert pertama kali
- **TIDAK PERLU** migration seperti SQL database
- Collection akan dibuat dengan nama `pengajuan_surat` saat pertama kali menyimpan data

---

## 📄 Struktur Document

### Contoh Document Lengkap

```json
{
  "_id": ObjectId("65a1b2c3d4e5f6g7h8i9j0k1"),
  "user_id": ObjectId("65a1b2c3d4e5f6g7h8i9j0k2"),
  "jenis_surat": "Surat Keterangan Kematian",
  "slug_layanan": "kematian",
  "no_pengajuan": "PJ-20250101-0001",
  
  "data_pelapor": {
    "nama_pelapor": "Budi Santoso",
    "nik_pelapor": "3201010101010001",
    "nomor_kk": "3201010101010001",
    "alamat_lengkap": "Jl. Raya Pabuaran Mekar No. 123, RT 01/RW 05",
    "nomor_telepon": "081234567890",
    "hubungan_dengan_pembuat_surat": "Anak"
  },
  
  "data_pembuat_surat": {
    "nama_pembuat_surat": "Budi Santoso",
    "nik_pembuat_surat": "3201010101010001",
    "alamat_ktp": "Jl. Raya Pabuaran Mekar No. 123, RT 01/RW 05"
  },
  
  "dokumen_upload": [
    {
      "key": "doc_pengantar_rt",
      "name": "Surat Pengantar RT/RW",
      "path": "storage/pengajuan/20250101_0_pengantar_rt.pdf",
      "type": "pdf",
      "size": 2048000,
      "required": true,
      "uploaded_at": "2025-01-01T10:00:00.000Z"
    },
    {
      "key": "doc_ktp_kk_alm",
      "name": "Fotokopi KTP dan KK Almarhum/Almarhumah",
      "path": "storage/pengajuan/20250101_1_ktp_kk_alm.pdf",
      "type": "pdf",
      "size": 1536000,
      "required": true,
      "uploaded_at": "2025-01-01T10:01:00.000Z"
    }
  ],
  
  "status": "menunggu",
  "tanggal_pengajuan": ISODate("2025-01-01T10:00:00.000Z"),
  "tanggal_diproses": null,
  "tanggal_selesai": null,
  "tanggal_ditolak": null,
  "estimasi_selesai": ISODate("2025-01-04T10:00:00.000Z"),
  "keterangan": "Pengajuan baru menunggu verifikasi",
  "alasan_penolakan": null,
  
  "memerlukan_etiket": true,
  "nomor_tiket": "ETK-2025-000001",
  "status_tiket": "Menunggu Verifikasi",
  "tanggal_tiket": null,
  "jam_tiket": null,
  
  "nomor_surat": null,
  "tanggal_surat": null,
  "file_surat_path": null,
  
  "created_by": ObjectId("65a1b2c3d4e5f6g7h8i9j0k2"),
  "processed_by": null,
  "completed_by": null,
  
  "created_at": ISODate("2025-01-01T10:00:00.000Z"),
  "updated_at": ISODate("2025-01-01T10:00:00.000Z")
}
```

---

## 🏷️ Jenis Layanan

### 5 Layanan Utama

1. **Surat Keterangan Kelahiran** (`slug: kelahiran`)
2. **Surat Keterangan Kematian** (`slug: kematian`)
3. **Surat Keterangan Usaha** (`slug: usaha`)
4. **Surat Keterangan Tidak Mampu** (`slug: tidak-mampu`)
5. **Pengantar PBB** (`slug: pbb`)

### Layanan yang Memerlukan e-Tiket

- Surat Keterangan Kematian
- Surat Keterangan Usaha
- Surat Keterangan Domisili
- Pengantar PBB

---

## 📊 Status Pengajuan

### Status Constants

```php
STATUS_MENUNGGU = 'menunggu'      // Baru diajukan, menunggu verifikasi
STATUS_DIPROSES = 'diproses'      // Sedang diproses oleh admin
STATUS_SELESAI = 'selesai'        // Sudah selesai, surat sudah dibuat
STATUS_DITOLAK = 'ditolak'        // Ditolak dengan alasan tertentu
```

### Status e-Tiket

- `Menunggu Verifikasi` - Default saat pengajuan baru
- `Disetujui` - Admin sudah approve, bisa datang ke kelurahan
- `Ditolak` - Pengajuan ditolak

---

## ⚙️ Konfigurasi MongoDB Atlas

### File `.env`

```env
# MongoDB Atlas Connection
DB_CONNECTION=mongodb
MONGO_URI=mongodb+srv://username:password@cluster.mongodb.net/kelurahan_pabuaran_mekar?retryWrites=true&w=majority
DB_DATABASE=kelurahan_pabuaran_mekar

# Atau gunakan MONGODB_URI (alternatif)
MONGODB_URI=mongodb+srv://username:password@cluster.mongodb.net/kelurahan_pabuaran_mekar?retryWrites=true&w=majority
```

### Cara Mendapatkan Connection String

1. Login ke [MongoDB Atlas](https://cloud.mongodb.com/)
2. Pilih cluster Anda
3. Klik "Connect"
4. Pilih "Connect your application"
5. Copy connection string
6. Ganti `<password>` dengan password database user Anda
7. Ganti `<database>` dengan nama database (opsional)

---

## 🔑 Indexes yang Disarankan

Untuk performa optimal, buat index berikut di MongoDB:

```javascript
// Index untuk user_id (untuk query pengajuan per user)
db.pengajuan_surat.createIndex({ "user_id": 1 })

// Index untuk no_pengajuan (unique)
db.pengajuan_surat.createIndex({ "no_pengajuan": 1 }, { unique: true })

// Index untuk nomor_tiket (unique, jika ada)
db.pengajuan_surat.createIndex({ "nomor_tiket": 1 }, { unique: true, sparse: true })

// Index untuk status dan tanggal (untuk filtering)
db.pengajuan_surat.createIndex({ "status": 1, "tanggal_pengajuan": -1 })

// Index untuk jenis_surat dan tanggal
db.pengajuan_surat.createIndex({ "jenis_surat": 1, "tanggal_pengajuan": -1 })
```

**Cara membuat index:**
- Via MongoDB Compass: Pilih collection → Indexes → Create Index
- Via MongoDB Shell: Jalankan perintah di atas
- Via Laravel: Tidak perlu, buat manual di MongoDB Atlas

---

## 📝 Catatan Penting

1. **Tidak Ada Migration**: MongoDB tidak memerlukan migration seperti SQL. Collection dibuat otomatis saat insert pertama.

2. **Flexible Schema**: MongoDB mendukung schema yang fleksibel. Field bisa berbeda antar document sesuai kebutuhan layanan.

3. **ObjectId**: MongoDB menggunakan `ObjectId` untuk `_id` dan relasi. Laravel MongoDB Eloquent otomatis handle konversi.

4. **Array Fields**: Field seperti `dokumen_upload`, `data_pelapor` disimpan sebagai array/object di MongoDB.

5. **Timestamps**: Laravel otomatis menambahkan `created_at` dan `updated_at` jika model menggunakan `timestamps`.

---

## 🔗 Relasi dengan Collection Lain

### Collection: `users`

```json
{
  "_id": ObjectId("65a1b2c3d4e5f6g7h8i9j0k2"),
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "nik_or_nip": "3201010101010001",
  "role": "user"
}
```

**Relasi:**
- `pengajuan_surat.user_id` → `users._id`
- Menggunakan `belongsTo` di Laravel Eloquent

---

## ✅ Validasi Data

### Field Wajib

- `user_id` - Harus ada (relasi ke users)
- `jenis_surat` - Harus ada
- `no_pengajuan` - Harus unique
- `data_pelapor` - Harus ada (minimal nama, NIK, alamat)
- `status` - Default: 'menunggu'
- `tanggal_pengajuan` - Default: now()

### Field Opsional

- `data_pembuat_surat` - Hanya untuk layanan tertentu
- `dokumen_upload` - Bisa kosong saat insert, diisi setelah upload
- `nomor_tiket` - Hanya untuk layanan yang memerlukan e-Tiket
- `nomor_surat` - Hanya setelah selesai

---

## 🚀 Next Steps

1. Setup MongoDB Atlas connection string di `.env`
2. Test connection dengan Tinker
3. Insert data dummy untuk testing
4. Buat index untuk performa optimal
5. Implementasi CRUD operations di Controller


# 📁 STRUKTUR VIEW ADMIN PENGAJUAN SURAT

## ✅ View yang Dibuat

### **1. `resources/views/admin/pengajuan/history.blade.php`**

**Fungsi**: Menampilkan riwayat perubahan status pengajuan

**Fitur**:
- Informasi pengajuan (nomor, jenis layanan, pemohon, status)
- Timeline riwayat perubahan status
- Menampilkan status lama → status baru
- Menampilkan catatan admin
- Menampilkan admin yang melakukan aksi
- Menampilkan waktu perubahan

**Data yang Diterima**:
- `$pengajuan` - Data pengajuan surat
- `$history` - Array riwayat perubahan status

**Route**: `GET /admin/pengajuan/{id}/history`

---

### **2. `resources/views/admin/pengajuan/detail.blade.php`**

**Fungsi**: Menampilkan detail lengkap pengajuan surat

**Fitur**:
- Informasi pengajuan lengkap
- Data pemohon
- Daftar dokumen pendukung
- Catatan admin
- Quick actions (Proses, Approve, Tolak, Revisi, Riwayat)

**Data yang Diterima**:
- `$pengajuan` - Data pengajuan surat dengan relasi user

**Route**: `GET /admin/pengajuan/{id}`

---

### **3. `resources/views/admin/pengajuan/index.blade.php`**

**Fungsi**: Menampilkan list semua pengajuan surat (alternatif view)

**Fitur**:
- Statistics cards (Total, Menunggu, Diproses, Selesai)
- Search dan filter
- Table list pengajuan
- Pagination
- Action buttons (Proses, Dokumen, Riwayat, Tolak, Revisi)

**Data yang Diterima**:
- `$pengajuan` - Paginated collection pengajuan
- `$stats` - Array statistics
- `$status` - Current filter status

**Route**: `GET /admin/pengajuan`

**Catatan**: Jika view ini tidak ada, controller akan fallback ke `admin.dashboard`

---

### **4. `resources/views/admin/partials/modals.blade.php`**

**Fungsi**: Partial view untuk modals (dokumen, reject, revise)

**Fitur**:
- Modal dokumen (menampilkan daftar dokumen)
- Modal reject (form alasan penolakan)
- Modal revise (form catatan perbaikan)
- JavaScript functions untuk handle modals

**Digunakan di**:
- `admin.dashboard`
- `admin.pengajuan.detail`
- `admin.pengajuan.index`

---

## 📂 Struktur Folder

```
resources/views/admin/
├── dashboard.blade.php          # Dashboard utama (menggunakan data MongoDB)
├── login.blade.php              # Login admin
└── pengajuan/
    ├── index.blade.php          # List pengajuan (alternatif)
    ├── detail.blade.php         # Detail pengajuan
    └── history.blade.php        # Riwayat pengajuan
└── partials/
    └── modals.blade.php         # Modals (dokumen, reject, revise)
```

---

## 🔗 Mapping Controller → View

| Controller Method | View | Route |
|------------------|------|-------|
| `AdminPengajuanController@index` | `admin.pengajuan.index` atau `admin.dashboard` | `GET /admin/pengajuan` |
| `AdminPengajuanController@show` | `admin.pengajuan.detail` | `GET /admin/pengajuan/{id}` |
| `AdminPengajuanController@showHistory` | `admin.pengajuan.history` | `GET /admin/pengajuan/{id}/history` |
| `AdminPengajuanController@showDocuments` | JSON response (AJAX) | `GET /admin/pengajuan/{id}/documents` |

---

## ✅ Checklist

- [x] View `admin.pengajuan.history` dibuat
- [x] View `admin.pengajuan.detail` dibuat
- [x] View `admin.pengajuan.index` dibuat (alternatif)
- [x] Partial `admin.partials.modals` dibuat
- [x] Controller method `showHistory()` diperbaiki
- [x] Path view sesuai dengan controller
- [x] Layout menggunakan `layouts.admin`
- [x] Modals bisa di-include di semua view

---

**SELESAI!** Semua view admin pengajuan surat sudah dibuat. 🎉


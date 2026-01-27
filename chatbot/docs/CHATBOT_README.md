# Chatbot Layanan Kelurahan - Dokumentasi

## 📁 Struktur Folder

```
/app
 └── Chatbot
     ├── Services
     │    └── KelurahanChatbotService.php
     ├── Controllers
     │    └── ChatbotController.php
     └── Prompts
          └── system_prompt.txt

/resources
 └── views
     └── components
          └── chatbot.blade.php

/public
 ├── css
 │    └── chatbot.css
 └── js
      └── chatbot.js
```

## 🔧 Konfigurasi

### 1. Environment Variables (.env)

Tambahkan ke file `.env`:

```env
AI_API_KEY=your_groq_api_key_here
# atau
GROQ_API_KEY=your_groq_api_key_here

GROQ_MODEL=llama-3.3-70b-versatile
GROQ_BASE_URL=https://api.groq.com/openai/v1
```

### 2. Dapatkan API Key

1. Daftar/login di: https://console.groq.com/
2. Buat API key baru
3. Copy dan paste ke `.env`

### 3. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 🚀 Routes

- **POST** `/api/chatbot/chat` - Endpoint utama chatbot
- **GET** `/api/chatbot/status` - Cek status konfigurasi API
- **POST** `/chatbot/ask` - Legacy endpoint (backward compatibility)

## 🎯 Fitur Chatbot

### 1. Menjawab Pertanyaan Layanan
- 12 layanan tersedia
- Informasi syarat, alur, dan estimasi
- Auto-generate link ke form pengajuan

### 2. List Layanan
User: "Apa saja layanan di sini?"
→ Bot menampilkan list 12 layanan dengan link

### 3. Cek Status Pengajuan
User: "Cek status PSM-2025-001"
→ Bot query MongoDB dan return status lengkap

### 4. Sapaan
User: "Halo", "Hai", "Selamat pagi"
→ Bot balas ramah + perkenalan

### 5. Filter Topik
- Hanya menjawab seputar layanan kelurahan
- Tolak sopan jika di luar konteks

## 📋 Layanan yang Tersedia

1. Surat Keterangan Domisili
2. Surat Keterangan Usaha
3. Surat Pengantar SKCK
4. Surat Keterangan Tidak Mampu
5. Surat Keterangan Kelahiran
6. Surat Keterangan Kematian
7. Surat Pindah
8. Surat Datang
9. Surat Izin Keramaian
10. Surat Keterangan Belum Menikah
11. Surat Keterangan Penghasilan
12. Surat Rekomendasi

## 🗄️ Database Integration

Chatbot terintegrasi dengan MongoDB collection:
- `pengajuan_surat` - Untuk cek status pengajuan

Query berdasarkan:
- Nomor Pengajuan (contoh: PSM-2025-001)
- ID Pengajuan

## 🎨 UI Features

- Floating button di pojok kanan bawah
- Sticky saat scroll (position: fixed)
- Responsive mobile & desktop
- Warna: Primary #1e40af, Accent #E9A500
- Auto-scroll ke pesan terbaru
- Loading indicator
- Error handling

## 🧪 Testing

1. Buka website
2. Klik tombol chat di pojok kanan bawah
3. Test dengan:
   - "Halo"
   - "Apa saja layanan di sini?"
   - "Bagaimana cara mengajukan surat domisili?"
   - "Cek status PSM-2025-001"

## 🐛 Troubleshooting

### Error: "API key tidak ditemukan"
- Pastikan `AI_API_KEY` atau `GROQ_API_KEY` sudah di-set di `.env`
- Jalankan `php artisan config:clear`

### Error: "Terjadi kesalahan saat menghubungi API"
- Cek apakah API key valid
- Cek koneksi internet
- Cek log di `storage/logs/laravel.log`

### Chatbot tidak muncul
- Pastikan component sudah di-include di layout
- Cek console browser (F12) untuk error JavaScript
- Pastikan CSS dan JS sudah di-load

## 📝 Notes

- Chatbot menggunakan model `llama-3.3-70b-versatile`
- System prompt disimpan di `app/Chatbot/Prompts/system_prompt.txt`
- Service class: `App\Chatbot\Services\KelurahanChatbotService`
- Controller: `App\Chatbot\Controllers\ChatbotController`


# Setup Chatbot Kelurahan

## Konfigurasi API Key

1. Buka file `.env` di root project
2. Tambahkan atau pastikan ada konfigurasi berikut:

```env
GROQ_API_KEY=your_api_key_here
GROQ_MODEL=llama-3.3-70b-versatile
GROQ_BASE_URL=https://api.groq.com/openai/v1
```

3. Dapatkan API Key dari: https://console.groq.com/

4. Setelah menambahkan API key, jalankan:
```bash
php artisan config:clear
php artisan cache:clear
```

## Test Chatbot

1. Buka browser console (F12)
2. Ketik "halo" di chatbot
3. Cek console untuk error detail jika masih error

## Troubleshooting

### Error: "Groq API key tidak ditemukan"
- Pastikan `GROQ_API_KEY` sudah di-set di `.env`
- Jalankan `php artisan config:clear`
- Restart server jika menggunakan `php artisan serve`

### Error: "Terjadi kesalahan saat menghubungi Groq API"
- Cek apakah API key valid
- Cek koneksi internet
- Cek log di `storage/logs/laravel.log`

### Error: "Tidak ada respons dari API"
- API key mungkin expired atau invalid
- Cek quota API di Groq console

## Cek Status API

Akses: `/api/chatbot/status` untuk mengecek apakah API key sudah terkonfigurasi.


# 📋 Rencana Reorganisasi Chatbot

## 📊 Analisis File Chatbot

### ✅ File yang MASIH DIGUNAKAN (JANGAN HAPUS):

#### Laravel Backend:
1. ✅ `app/Chatbot/Controllers/ChatbotController.php` - Digunakan di `routes/web.php`
2. ✅ `app/Chatbot/Services/ChatbotService.php` - Service utama (fallback)
3. ✅ `app/Chatbot/Services/PythonChatbotClient.php` - Client untuk Python service
4. ✅ `app/Chatbot/Helpers/IntentDetector.php` - Digunakan di ChatbotService (fallback)
5. ✅ `app/Chatbot/Prompts/SystemPrompt.php` - Digunakan di ChatbotService (fallback)
6. ✅ `app/Chatbot/Prompts/system_prompt.txt` - Digunakan di SystemPrompt
7. ✅ `config/chatbot.php` - Config file

#### Frontend:
8. ✅ `public/chatbot/chatbot.js` - Digunakan di `resources/views/chatbot/widget.blade.php`
9. ✅ `public/chatbot/chatbot.css` - Digunakan di `resources/views/chatbot/widget.blade.php`
10. ✅ `resources/views/chatbot/widget.blade.php` - Digunakan di `resources/views/layouts/app.blade.php`

#### Python Service:
11. ✅ `chatbot-python/` - Python FastAPI service (AKTIF)

### ⚠️ File yang PERLU DICEK:

1. ⚠️ `app/Services/GroqService.php` - Perlu cek apakah masih digunakan
2. ⚠️ `resources/chatbot_prompt.txt` - Digunakan di GroqService (jika masih aktif)

### 📄 Dokumentasi (Bisa dipindah ke folder docs):
- `CHATBOT_PYTHON_MIGRATION.md`
- `CHATBOT_README.md`
- `CHATBOT_SETUP.md`
- `QUICK_START_PYTHON_CHATBOT.md`
- `MIGRATION_SUMMARY.md`

---

## 🎯 Struktur Baru yang Diusulkan

```
chatbot/
├── python/                    # Python FastAPI service
│   ├── app/
│   ├── Dockerfile
│   ├── requirements.txt
│   └── README.md
│
├── laravel/                   # Laravel integration
│   ├── Controllers/
│   │   └── ChatbotController.php
│   ├── Services/
│   │   ├── ChatbotService.php
│   │   └── PythonChatbotClient.php
│   ├── Helpers/
│   │   └── IntentDetector.php
│   └── Prompts/
│       ├── SystemPrompt.php
│       └── system_prompt.txt
│
├── frontend/                  # Frontend files
│   ├── js/
│   │   └── chatbot.js
│   ├── css/
│   │   └── chatbot.css
│   └── views/
│       └── widget.blade.php
│
├── config/                    # Configuration
│   └── chatbot.php
│
└── docs/                      # Dokumentasi
    ├── CHATBOT_PYTHON_MIGRATION.md
    ├── CHATBOT_README.md
    ├── CHATBOT_SETUP.md
    ├── QUICK_START_PYTHON_CHATBOT.md
    └── MIGRATION_SUMMARY.md
```

---

## 📝 Langkah-langkah Reorganisasi

### **Fase 1: Buat Struktur Folder**
1. ✅ Buat folder `chatbot/` di root
2. ✅ Buat subfolder: `python/`, `laravel/`, `frontend/`, `config/`, `docs/`

### **Fase 2: Pindahkan File**
1. ✅ Pindahkan `chatbot-python/` → `chatbot/python/`
2. ✅ Pindahkan `app/Chatbot/` → `chatbot/laravel/`
3. ✅ Pindahkan `public/chatbot/` → `chatbot/frontend/`
4. ✅ Pindahkan `resources/views/chatbot/` → `chatbot/frontend/views/`
5. ✅ Pindahkan `config/chatbot.php` → `chatbot/config/`
6. ✅ Pindahkan dokumentasi → `chatbot/docs/`

### **Fase 3: Update Referensi**
1. ✅ Update namespace di Laravel files
2. ✅ Update path di routes
3. ✅ Update asset path di views
4. ✅ Update config path
5. ✅ Update autoload di composer.json

### **Fase 4: Cleanup**
1. ✅ Hapus folder lama yang sudah kosong
2. ✅ Hapus file yang tidak digunakan (setelah konfirmasi)

---

## ⚠️ PERHATIAN

**SEBELUM MENGHAPUS FILE:**
- ✅ Cek semua referensi
- ✅ Test project masih bisa jalan
- ✅ Backup file penting

**JANGAN HAPUS:**
- ❌ File yang masih direferensikan
- ❌ File yang digunakan untuk fallback
- ❌ Config files yang aktif

---

## ✅ Checklist Final

- [ ] Struktur folder `chatbot/` sudah dibuat
- [ ] Semua file sudah dipindahkan
- [ ] Semua referensi sudah diupdate
- [ ] Project masih bisa dijalankan
- [ ] Tidak ada error
- [ ] File yang tidak digunakan sudah dihapus
- [ ] Dokumentasi sudah lengkap


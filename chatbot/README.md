# 🤖 Chatbot Module

Folder ini berisi semua file yang berkaitan dengan chatbot system.

## 📁 Struktur Folder

```
chatbot/
├── python/              # Python FastAPI service
│   ├── app/            # Python application code
│   ├── Dockerfile      # Docker configuration
│   ├── requirements.txt # Python dependencies
│   └── README.md       # Python service documentation
│
├── laravel/            # Laravel integration (source files)
│   ├── Controllers/    # Chatbot controllers
│   ├── Services/       # Chatbot services
│   ├── Helpers/       # Helper classes
│   └── Prompts/        # System prompts
│
├── frontend/           # Frontend files
│   ├── js/            # JavaScript files
│   ├── css/           # CSS files
│   └── views/         # Blade templates
│
├── config/            # Configuration files
│   └── chatbot.php    # Chatbot configuration
│
└── docs/              # Documentation
    ├── CHATBOT_PYTHON_MIGRATION.md
    ├── CHATBOT_README.md
    ├── CHATBOT_SETUP.md
    ├── QUICK_START_PYTHON_CHATBOT.md
    └── MIGRATION_SUMMARY.md
```

## ⚠️ Catatan Penting

### **Laravel Files Location**

File Laravel (PHP) **tetap berada di `app/Chatbot/`** karena:
- Laravel memerlukan namespace `App\Chatbot`
- Autoload PSR-4 memerlukan struktur folder sesuai namespace
- File di `chatbot/laravel/` adalah **source/reference** copy

**File aktif yang digunakan:**
- `app/Chatbot/` → File yang digunakan oleh Laravel
- `chatbot/laravel/` → Copy untuk dokumentasi/reference

### **Frontend Files Location**

File frontend **ada di 2 tempat**:
- `chatbot/frontend/` → Source files
- `public/chatbot/` → Files yang di-serve oleh Laravel (harus ada di public)
- `resources/views/chatbot/` → Blade templates (harus ada di resources/views)

**File aktif yang digunakan:**
- `public/chatbot/js/chatbot.js` → JavaScript yang di-load browser
- `public/chatbot/css/chatbot.css` → CSS yang di-load browser
- `resources/views/chatbot/widget.blade.php` → View template

### **Python Service**

Python service **berada di `chatbot/python/`** dan berjalan sebagai service terpisah.

## 🔄 Sync Files

Jika ada perubahan di `chatbot/laravel/`, sync ke `app/Chatbot/`:
```bash
robocopy chatbot\laravel app\Chatbot /E
```

Jika ada perubahan di `chatbot/frontend/`, sync ke `public/chatbot/` dan `resources/views/chatbot/`:
```bash
robocopy chatbot\frontend\js public\chatbot\js /E
robocopy chatbot\frontend\css public\chatbot\css /E
robocopy chatbot\frontend\views resources\views\chatbot /E
```

## 📚 Dokumentasi

Lihat folder `chatbot/docs/` untuk dokumentasi lengkap:
- `CHATBOT_PYTHON_MIGRATION.md` - Panduan migrasi ke Python
- `QUICK_START_PYTHON_CHATBOT.md` - Quick start guide
- `CHATBOT_SETUP.md` - Setup instructions

## 🚀 Quick Start

1. **Python Service:**
   ```bash
   cd chatbot/python
   pip install -r requirements.txt
   uvicorn app.main:app --host 0.0.0.0 --port 8001
   ```

2. **Laravel:**
   - File sudah di `app/Chatbot/`
   - Config di `config/chatbot.php`
   - Routes di `routes/web.php`

3. **Frontend:**
   - JS: `public/chatbot/js/chatbot.js`
   - CSS: `public/chatbot/css/chatbot.css`
   - View: `resources/views/chatbot/widget.blade.php`

---

**Last Updated:** 2026-01-26


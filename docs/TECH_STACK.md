# 🛠️ Teknologi yang Digunakan di Website Kelurahan Pabuaran Mekar

Dokumen ini menjelaskan semua teknologi, framework, library, dan tools yang digunakan dalam pengembangan website Kelurahan Pabuaran Mekar.

---

## 📋 Daftar Isi

1. [Backend Framework](#backend-framework)
2. [Database](#database)
3. [Frontend Technologies](#frontend-technologies)
4. [AI & Chatbot Services](#ai--chatbot-services)
5. [Development Tools](#development-tools)
6. [Server & Deployment](#server--deployment)
7. [API & Integration](#api--integration)

---

## 🎯 Backend Framework

### **Laravel 12.0**
- **Deskripsi**: PHP framework modern untuk pengembangan web application
- **Versi**: ^12.0
- **Kegunaan**: 
  - Backend utama aplikasi
  - Routing, middleware, authentication
  - MVC architecture
  - Database ORM (Eloquent)
  - Session management
  - Queue system
- **File**: `composer.json`

### **PHP 8.2+**
- **Versi**: ^8.2
- **Kegunaan**: Bahasa pemrograman backend
- **Fitur yang digunakan**:
  - Type declarations
  - Attributes
  - Named arguments
  - Match expressions

---

## 💾 Database

### **MongoDB (Primary Database)**
- **Package**: `mongodb/laravel-mongodb` ^6.0
- **Kegunaan**: 
  - Database utama untuk menyimpan data pengguna, pengajuan surat, dll
  - NoSQL database untuk fleksibilitas schema
  - Support untuk MongoDB Atlas (cloud)
- **Konfigurasi**: `config/database.php`
- **Connection String**: Menggunakan DSN format untuk MongoDB Atlas
- **Default Connection**: `DB_CONNECTION=mongodb`

### **Laravel Database Cache**
- **Driver**: `database` (default)
- **Kegunaan**: 
  - Cache management menggunakan database
  - Session storage menggunakan database
- **Konfigurasi**: 
  - `config/cache.php` → `CACHE_STORE=database`
  - `config/session.php` → `SESSION_DRIVER=database`

> **Catatan**: Redis dan SQLite tersedia di konfigurasi Laravel tetapi **tidak digunakan** dalam aplikasi ini. Aplikasi menggunakan MongoDB sebagai database utama dan database cache/session untuk caching.

---

## 🎨 Frontend Technologies

### **Tailwind CSS 4.0**
- **Versi**: ^4.0.0
- **Package**: `tailwindcss`, `@tailwindcss/vite`
- **Kegunaan**: 
  - Utility-first CSS framework
  - Styling website
  - Responsive design
- **Konfigurasi**: `vite.config.js`

### **Vite 7.0**
- **Versi**: ^7.0.0
- **Package**: `vite`, `laravel-vite-plugin`
- **Kegunaan**: 
  - Build tool untuk frontend assets
  - Fast HMR (Hot Module Replacement)
  - Asset bundling dan optimization
- **Konfigurasi**: `vite.config.js`

### **Fetch API (Native JavaScript)**
- **Kegunaan**: 
  - HTTP client untuk AJAX requests (native browser API)
  - API communication dari frontend
  - Digunakan di `chatbot.js` untuk komunikasi dengan Laravel API
- **Catatan**: Axios tersedia di `package.json` tetapi **tidak digunakan**. Aplikasi menggunakan Fetch API yang native di browser.

### **Vanilla JavaScript (ES6+)**
- **Kegunaan**: 
  - Frontend interactivity (tanpa framework JavaScript)
  - Chatbot widget (`chatbot.js`)
  - Form handling
  - DOM manipulation
  - AJAX requests menggunakan Fetch API
- **File**: `public/chatbot/js/chatbot.js`
- **Catatan**: Aplikasi ini **tidak menggunakan** framework JavaScript seperti React, Vue, atau Angular. Semua interaktivitas menggunakan vanilla JavaScript murni.

### **Blade Templates**
- **Kegunaan**: 
  - Laravel templating engine
  - Server-side rendering
  - Component-based views
- **File**: `resources/views/`

---

## 🤖 AI & Chatbot Services

### **Python FastAPI**
- **Framework**: FastAPI 0.104.1
- **Server**: Uvicorn 0.24.0
- **Kegunaan**: 
  - Microservice untuk chatbot AI
  - RESTful API untuk pemrosesan pesan chatbot
  - Intent detection
  - Context building
- **File**: `chatbot/python/app/main.py`
- **Port**: 8001 (default)

### **Python Libraries**
- **Pydantic 2.5.0**: Data validation dan serialization
- **Pydantic Settings 2.1.0**: Configuration management
- **HTTPX 0.25.1**: Async HTTP client untuk API calls
- **Python-dotenv 1.0.0**: Environment variable management
- **Python-multipart 0.0.6**: Form data handling

### **Groq AI API**
- **Service**: Groq Cloud AI
- **Model**: `llama-3.3-70b-versatile` (default)
- **Kegunaan**: 
  - AI-powered chatbot responses
  - Natural language processing
  - Conversational AI
- **API Endpoint**: `https://api.groq.com/openai/v1`
- **Konfigurasi**: `config/services.php`
- **File**: 
  - `app/Services/GroqService.php` (PHP)
  - `chatbot/python/app/services/ai_service.py` (Python)

---

## 🛠️ Development Tools

### **Composer**
- **Kegunaan**: PHP dependency manager
- **File**: `composer.json`, `composer.lock`

### **NPM (Node Package Manager)**
- **Kegunaan**: JavaScript dependency manager
- **File**: `package.json`, `package-lock.json`

### **Laravel Tinker**
- **Versi**: ^2.10.1
- **Kegunaan**: REPL untuk Laravel (interactive shell)

### **Laravel Pint**
- **Versi**: ^1.24
- **Kegunaan**: Code style fixer (PHP CS Fixer wrapper)

### **Laravel Pail**
- **Versi**: ^1.2.2
- **Kegunaan**: Real-time log viewer

### **Laravel Sail**
- **Versi**: ^1.41
- **Kegunaan**: Docker development environment

### **PHPUnit**
- **Versi**: ^11.5.3
- **Kegunaan**: Unit testing framework
- **Konfigurasi**: `phpunit.xml`

### **Faker**
- **Versi**: ^1.23
- **Kegunaan**: Generate fake data untuk testing

### **Mockery**
- **Versi**: ^1.6
- **Kegunaan**: Mocking framework untuk testing

### **Concurrently**
- **Versi**: ^9.0.1
- **Kegunaan**: Run multiple commands simultaneously
- **Penggunaan**: Development script untuk menjalankan server, queue, logs, dan vite bersamaan

---

## 🚀 Server & Deployment

### **Web Server**
- **PHP Built-in Server**: Development (`php artisan serve`)
- **Apache/Nginx**: Production (recommended)

### **Application Server**
- **Uvicorn**: Python ASGI server untuk FastAPI chatbot service

### **Process Manager**
- **Laravel Queue Worker**: Background job processing
- **Supervisor/PM2**: Process management (production)

---

## 🔌 API & Integration

### **RESTful API**
- **Laravel API Routes**: 
  - `/api/chatbot/message` - Chatbot endpoint
  - `/api/chatbot/status` - Chatbot health check
- **Python FastAPI Endpoints**:
  - `GET /health` - Health check
  - `POST /api/v1/chat` - Chat processing

### **HTTP Client**
- **Laravel HTTP Facade**: HTTP requests dari PHP
- **HTTPX (Python)**: Async HTTP client untuk Python

### **CORS (Cross-Origin Resource Sharing)**
- **FastAPI CORS Middleware**: Enable CORS untuk chatbot service
- **Konfigurasi**: `chatbot/python/app/main.py`

---

## 📦 Package Dependencies Summary

### **PHP Dependencies (Composer)**
```json
{
  "php": "^8.2",
  "mongodb/laravel-mongodb": "^6.0",
  "laravel/framework": "^12.0",
  "laravel/tinker": "^2.10.1"
}
```

### **JavaScript Dependencies (NPM)**
```json
{
  "@tailwindcss/vite": "^4.0.0",
  "axios": "^1.11.0",
  "concurrently": "^9.0.1",
  "laravel-vite-plugin": "^2.0.0",
  "tailwindcss": "^4.0.0",
  "vite": "^7.0.7"
}
```

### **Python Dependencies**
```
fastapi==0.104.1
uvicorn[standard]==0.24.0
pydantic==2.5.0
pydantic-settings==2.1.0
httpx==0.25.1
python-dotenv==1.0.0
python-multipart==0.0.6
```

---

## 🏗️ Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                    CLIENT (Browser)                         │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │  HTML/CSS    │  │  JavaScript  │  │  Tailwind    │      │
│  │  (Blade)     │  │  (Vanilla)   │  │  CSS         │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└───────────────────────┬─────────────────────────────────────┘
                        │ HTTP/HTTPS
                        ▼
┌─────────────────────────────────────────────────────────────┐
│              LARAVEL APPLICATION (PHP 8.2)                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │  Controllers │  │  Middleware  │  │   Models    │      │
│  │  Routes      │  │  Auth        │  │   Eloquent  │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐  │
│  │         ChatbotService (PHP)                        │  │
│  │  ┌──────────────────────────────────────────────┐   │  │
│  │  │  PythonChatbotClient (HTTP Client)           │   │  │
│  │  └──────────────────┬───────────────────────────┘   │  │
│  └─────────────────────┼───────────────────────────────┘  │
└─────────────────────────┼───────────────────────────────────┘
                          │ HTTP POST
                          ▼
┌─────────────────────────────────────────────────────────────┐
│         PYTHON FASTAPI SERVICE (Port 8001)                   │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │  FastAPI     │  │  Intent      │  │  Context     │      │
│  │  Endpoints   │  │  Detector    │  │  Builder     │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐  │
│  │         AIService (Python)                          │  │
│  │  ┌──────────────────────────────────────────────┐   │  │
│  │  │  HTTPX Client → Groq AI API                  │   │  │
│  │  └──────────────────────────────────────────────┘   │  │
│  └─────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                          │ HTTP POST
                          ▼
┌─────────────────────────────────────────────────────────────┐
│              EXTERNAL SERVICES                               │
│  ┌──────────────┐              ┌──────────────┐            │
│  │  Groq AI API │              │  MongoDB     │            │
│  │  (LLM)       │              │  Atlas       │            │
│  └──────────────┘              └──────────────┘            │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔐 Security Features

1. **CSRF Protection**: Laravel built-in CSRF tokens
2. **Authentication**: Laravel authentication system
3. **API Key Authentication**: Bearer token untuk Python service
4. **Input Validation**: Laravel validation rules
5. **SQL Injection Protection**: Eloquent ORM (parameterized queries)
6. **XSS Protection**: Blade template escaping

---

## 📝 Environment Variables

File `.env` berisi konfigurasi penting:

```env
# Application
APP_NAME="Kelurahan Pabuaran Mekar"
APP_ENV=local|production
APP_DEBUG=true|false

# Database
DB_CONNECTION=mongodb
MONGO_URI=mongodb+srv://...
DB_DATABASE=kelurahan_pabuaran_mekar

# Cache & Session (menggunakan database)
CACHE_STORE=database
SESSION_DRIVER=database

# AI Service
GROQ_API_KEY=your_groq_api_key
GROQ_BASE_URL=https://api.groq.com/openai/v1
GROQ_MODEL=llama-3.3-70b-versatile

# Python Chatbot
PYTHON_CHATBOT_URL=http://localhost:8001
PYTHON_CHATBOT_API_KEY=your_api_key
PYTHON_CHATBOT_ENABLED=true
```

---

## 🎯 Technology Stack Summary

| Kategori | Teknologi | Versi | Kegunaan |
|----------|-----------|-------|----------|
| **Backend** | Laravel | 12.0 | Main framework |
| **Backend** | PHP | 8.2+ | Programming language |
| **Database** | MongoDB | Latest | Primary database |
| **Database** | Database Cache | Laravel | Cache & sessions (via MongoDB) |
| **Frontend** | Tailwind CSS | 4.0 | Styling |
| **Frontend** | Vite | 7.0 | Build tool |
| **Frontend** | Vanilla JavaScript | ES6+ | Interactivity (no framework) |
| **Frontend** | Blade Templates | Laravel | Server-side templating |
| **AI Service** | Python | 3.x | Chatbot backend |
| **AI Service** | FastAPI | 0.104 | API framework |
| **AI Service** | Groq AI | Latest | LLM provider |
| **Tools** | Composer | Latest | PHP package manager |
| **Tools** | NPM | Latest | JS package manager |
| **Testing** | PHPUnit | 11.5 | Unit testing |

---

## 📚 Dokumentasi Tambahan

- **Laravel**: https://laravel.com/docs
- **MongoDB Laravel**: https://github.com/mongodb/laravel-mongodb
- **FastAPI**: https://fastapi.tiangolo.com/
- **Tailwind CSS**: https://tailwindcss.com/docs
- **Groq AI**: https://groq.com/

---

**Last Updated**: 2026-01-26
**Maintained by**: Development Team


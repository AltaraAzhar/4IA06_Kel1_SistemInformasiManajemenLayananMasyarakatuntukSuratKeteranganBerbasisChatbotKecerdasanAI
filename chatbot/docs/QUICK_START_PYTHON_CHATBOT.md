# 🚀 Quick Start: Python Chatbot Migration

## ⚡ Setup Cepat (5 Menit)

### **1. Setup Python Service**

```bash
# Masuk ke folder chatbot-python
cd chatbot-python

# Install dependencies
pip install -r requirements.txt

# Copy dan edit .env
cp .env.example .env
# Edit .env dengan API key Anda
```

### **2. Update Laravel .env**

```env
# Python Chatbot Service
PYTHON_CHATBOT_URL=http://localhost:8001
PYTHON_CHATBOT_API_KEY=your-secret-api-key-here
PYTHON_CHATBOT_TIMEOUT=30
PYTHON_CHATBOT_ENABLED=true
```

### **3. Jalankan Python Service**

```bash
# Di terminal 1: Python service
cd chatbot-python
uvicorn app.main:app --host 0.0.0.0 --port 8001 --reload

# Di terminal 2: Laravel
php artisan serve
```

### **4. Test**

```bash
# Test health check
curl http://localhost:8001/health

# Test chat endpoint
curl -X POST http://localhost:8001/api/v1/chat \
  -H "Authorization: Bearer your-secret-api-key-here" \
  -H "Content-Type: application/json" \
  -d '{
    "message": "Saya ingin membuat surat keterangan tidak mampu",
    "conversation_history": [],
    "user_id": null
  }'
```

## ✅ Checklist

- [ ] Python service running di port 8001
- [ ] Laravel running di port 8000
- [ ] Health check berhasil
- [ ] Chat endpoint berhasil
- [ ] Frontend bisa kirim pesan ke Laravel
- [ ] Laravel forward ke Python
- [ ] Response kembali ke frontend

## 🔧 Troubleshooting

**Python service tidak bisa diakses:**
- Cek port 8001 tidak digunakan aplikasi lain
- Cek firewall/antivirus
- Cek `.env` configuration

**Laravel tidak bisa connect ke Python:**
- Cek `PYTHON_CHATBOT_URL` di Laravel `.env`
- Cek `PYTHON_CHATBOT_API_KEY` sama dengan Python `.env`
- Test dengan `curl` atau Postman

**Response error:**
- Cek logs Python: `tail -f logs/app.log`
- Cek logs Laravel: `storage/logs/laravel.log`
- Cek Groq API key valid

---

**Selamat! Python chatbot service sudah siap digunakan! 🎉**


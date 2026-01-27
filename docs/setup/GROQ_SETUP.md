# Setup Groq API untuk Chatbot

## 📋 Langkah-langkah Setup

### 1. Dapatkan API Key Groq

1. Kunjungi [https://console.groq.com/](https://console.groq.com/)
2. Buat akun atau login
3. Buka bagian "API Keys"
4. Buat API Key baru
5. Salin API Key yang diberikan

### 2. Tambahkan ke File .env

Buka file `.env` di root project Anda dan tambahkan:

```env
GROQ_API_KEY=your_groq_api_key_here
GROQ_BASE_URL=https://api.groq.com/openai/v1
GROQ_MODEL=llama-3.1-8b-instant
```

**Catatan:**
- `GROQ_API_KEY`: Wajib diisi dengan API key dari Groq Console
- `GROQ_BASE_URL`: Optional, default sudah sesuai
- `GROQ_MODEL`: Optional, default `llama-3.1-8b-instant`. Model lain yang tersedia:
  - `llama-3.1-8b-instant` (Cepat, efisien)
  - `llama-3.1-70b-versatile` (Lebih akurat)
  - `mixtral-8x7b-32768`
  - `gemma-7b-it`

### 3. Clear Config Cache (Opsional)

Jika sudah pernah menjalankan aplikasi, jalankan:

```bash
php artisan config:clear
```

## 🚀 Cara Menggunakan

### API Endpoints

#### 1. Cek Status Konfigurasi

```bash
GET /api/chatbot/status
```

**Response:**
```json
{
  "configured": true,
  "message": "Groq API sudah dikonfigurasi"
}
```

#### 2. Chat dengan Chatbot

```bash
POST /api/chatbot/chat
Content-Type: application/json
```

**Request Body:**
```json
{
  "message": "Apa saja layanan yang tersedia?",
  "conversation_history": []
}
```

**Dengan Conversation History:**
```json
{
  "message": "Bagaimana cara mengajukan surat keterangan domisili?",
  "conversation_history": [
    {
      "role": "user",
      "content": "Halo"
    },
    {
      "role": "assistant",
      "content": "Halo! Selamat datang. Ada yang bisa saya bantu?"
    }
  ]
}
```

**Response Success:**
```json
{
  "success": true,
  "message": "Layanan yang tersedia di Kelurahan Pabuaran Mekar adalah...",
  "usage": {
    "prompt_tokens": 50,
    "completion_tokens": 100,
    "total_tokens": 150
  },
  "model": "llama-3.1-8b-instant"
}
```

**Response Error:**
```json
{
  "success": false,
  "error": "Groq API key tidak ditemukan..."
}
```

## 💻 Contoh Penggunaan di JavaScript/Frontend

### Vanilla JavaScript

```javascript
async function sendChatMessage(message, conversationHistory = []) {
    try {
        const response = await fetch('/api/chatbot/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                message: message,
                conversation_history: conversationHistory
            })
        });

        const data = await response.json();

        if (data.success) {
            console.log('Bot response:', data.message);
            return data.message;
        } else {
            console.error('Error:', data.error);
            return null;
        }
    } catch (error) {
        console.error('Network error:', error);
        return null;
    }
}

// Contoh penggunaan
sendChatMessage("Apa saja layanan kelurahan?", [])
    .then(response => {
        if (response) {
            // Tampilkan response ke user
            console.log(response);
        }
    });
```

### Axios (jika menggunakan Axios)

```javascript
import axios from 'axios';

async function chatWithBot(message, conversationHistory = []) {
    try {
        const response = await axios.post('/api/chatbot/chat', {
            message: message,
            conversation_history: conversationHistory
        });

        return response.data.message;
    } catch (error) {
        console.error('Error:', error.response?.data?.error || error.message);
        return null;
    }
}
```

## 🔧 Konfigurasi Lanjutan

### Mengubah Model AI

Edit file `.env`:
```env
GROQ_MODEL=llama-3.1-70b-versatile
```

Kemudian clear config:
```bash
php artisan config:clear
```

### Custom System Prompt

Edit file `app/Services/GroqService.php`, method `getKelurahanSystemPrompt()` untuk mengubah prompt sistem chatbot.

## 📝 Catatan Penting

1. **API Key Security**: Jangan commit file `.env` ke repository. Pastikan `.env` ada di `.gitignore`
2. **Rate Limiting**: Groq API memiliki rate limit. Monitor penggunaan di Groq Console
3. **Error Handling**: Selalu handle error response dengan baik di frontend
4. **Token Usage**: Monitor usage token untuk kontrol biaya

## ✅ Testing

Test endpoint dengan cURL:

```bash
# Cek status
curl http://localhost:8000/api/chatbot/status

# Test chat
curl -X POST http://localhost:8000/api/chatbot/chat \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{"message":"Halo"}'
```

## 🆘 Troubleshooting

### Error: "Groq API key tidak ditemukan"
- Pastikan `GROQ_API_KEY` sudah ditambahkan di `.env`
- Jalankan `php artisan config:clear`
- Restart web server

### Error: "401 Unauthorized"
- Cek API key di Groq Console
- Pastikan API key masih valid

### Error: "429 Too Many Requests"
- Rate limit terlampaui
- Tunggu beberapa saat atau upgrade plan Groq


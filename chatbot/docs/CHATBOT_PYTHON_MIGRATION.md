# 🤖 Migrasi Chatbot Laravel → Python (FastAPI)

## 📋 Daftar Isi
1. [Arsitektur](#arsitektur)
2. [Struktur Folder](#struktur-folder)
3. [Flow Request](#flow-request)
4. [Setup & Instalasi](#setup--instalasi)
5. [Contoh Kode](#contoh-kode)
6. [Keamanan](#keamanan)
7. [Deployment](#deployment)

---

## 🏗️ Arsitektur

```
┌─────────────┐         HTTP API         ┌──────────────┐
│   Frontend   │ ────────────────────────> │   Laravel    │
│   (JS/HTML)  │ <──────────────────────── │  Controller  │
└─────────────┘         JSON Response     └──────┬───────┘
                                                 │
                                                 │ HTTP Request
                                                 │ (with auth token)
                                                 ▼
                                          ┌──────────────┐
                                          │  Python      │
                                          │  FastAPI     │
                                          │  Chatbot     │
                                          │  Service     │
                                          └──────────────┘
                                                 │
                                                 │ AI API Call
                                                 ▼
                                          ┌──────────────┐
                                          │  Groq/OpenAI │
                                          │  API         │
                                          └──────────────┘
```

### **Komponen:**
1. **Frontend (JS)**: Mengirim pesan ke Laravel
2. **Laravel Controller**: Menerima request, validasi, forward ke Python
3. **Python FastAPI**: Proses NLP, intent detection, AI response
4. **AI API (Groq/OpenAI)**: Generate response

---

## 📁 Struktur Folder

### **Laravel (Existing)**
```
app/
├── Chatbot/
│   ├── Controllers/
│   │   └── ChatbotController.php      # Tetap ada, forward ke Python
│   ├── Services/
│   │   ├── ChatbotService.php         # Update: HTTP client ke Python
│   │   └── PythonChatbotClient.php   # NEW: Client untuk Python API
│   └── Config/
│       └── chatbot.php                # NEW: Config Python service
```

### **Python (New)**
```
chatbot-python/
├── app/
│   ├── __init__.py
│   ├── main.py                         # FastAPI app entry point
│   ├── config.py                       # Configuration
│   ├── models/
│   │   ├── __init__.py
│   │   ├── request.py                  # Request models
│   │   └── response.py                 # Response models
│   ├── services/
│   │   ├── __init__.py
│   │   ├── ai_service.py               # AI API integration
│   │   ├── intent_detector.py          # Intent detection
│   │   └── context_builder.py          # Context building
│   ├── routers/
│   │   ├── __init__.py
│   │   └── chatbot.py                  # Chatbot endpoints
│   └── utils/
│       ├── __init__.py
│       ├── auth.py                     # Auth middleware
│       └── logger.py                   # Logging
├── requirements.txt
├── .env.example
├── Dockerfile                          # Optional: Docker
└── README.md
```

---

## 🔄 Flow Request

### **1. User mengirim pesan (Frontend → Laravel)**
```javascript
// Frontend (chatbot.js)
fetch('/api/chatbot/message', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
        message: 'Saya ingin membuat surat keterangan tidak mampu',
        conversation_history: [...]
    })
})
```

### **2. Laravel Controller menerima request**
```php
// Laravel: ChatbotController.php
public function message(Request $request) {
    // Validasi
    // Forward ke Python service
    $response = $this->pythonChatbotClient->sendMessage(...);
    return response()->json($response);
}
```

### **3. Laravel forward ke Python (HTTP Request)**
```php
// Laravel: PythonChatbotClient.php
$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $apiToken,
    'Content-Type' => 'application/json'
])->post('http://python-service:8000/api/v1/chat', [
    'message' => $message,
    'conversation_history' => $history,
    'user_id' => $userId,
    'context' => $context
]);
```

### **4. Python FastAPI memproses**
```python
# Python: main.py
@app.post("/api/v1/chat")
async def chat(request: ChatRequest):
    # 1. Detect intent
    intent = intent_detector.detect(request.message)
    
    # 2. Build context
    context = context_builder.build(request.user_id)
    
    # 3. Call AI
    ai_response = ai_service.generate_response(
        message=request.message,
        context=context,
        history=request.conversation_history
    )
    
    # 4. Return response
    return ChatResponse(
        reply=ai_response,
        links=[],
        requires_login=False
    )
```

### **5. Response kembali ke Laravel → Frontend**
```json
{
    "reply": "Untuk membuat surat keterangan tidak mampu...",
    "links": [
        {
            "text": "Formulir SKTM",
            "url": "/user/pengajuan/form/tidak-mampu"
        }
    ],
    "requires_login": false
}
```

---

## 🚀 Setup & Instalasi

### **1. Setup Python Service**

```bash
# Buat folder baru
mkdir chatbot-python
cd chatbot-python

# Virtual environment
python -m venv venv
source venv/bin/activate  # Linux/Mac
# atau
venv\Scripts\activate     # Windows

# Install dependencies
pip install -r requirements.txt
```

### **2. Environment Variables**

**Python (.env)**
```env
# Server
HOST=0.0.0.0
PORT=8000
DEBUG=False

# Security
API_KEY=your-secret-api-key-here
ALLOWED_ORIGINS=http://localhost,http://localhost:8000

# AI API
GROQ_API_KEY=your-groq-api-key
GROQ_BASE_URL=https://api.groq.com/openai/v1
GROQ_MODEL=llama-3.3-70b-versatile

# Laravel Integration
LARAVEL_URL=http://localhost:8000
```

**Laravel (.env)**
```env
# Python Chatbot Service
PYTHON_CHATBOT_URL=http://localhost:8001
PYTHON_CHATBOT_API_KEY=your-secret-api-key-here
PYTHON_CHATBOT_TIMEOUT=30
```

---

## 💻 Contoh Kode

### **1. Python FastAPI Main (app/main.py)**

```python
from fastapi import FastAPI, HTTPException, Depends, Header
from fastapi.middleware.cors import CORSMiddleware
from app.models.request import ChatRequest
from app.models.response import ChatResponse
from app.services.ai_service import AIService
from app.services.intent_detector import IntentDetector
from app.services.context_builder import ContextBuilder
from app.utils.auth import verify_api_key
from app.config import settings
import logging

app = FastAPI(title="Chatbot API", version="1.0.0")

# CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=settings.ALLOWED_ORIGINS,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Services
ai_service = AIService()
intent_detector = IntentDetector()
context_builder = ContextBuilder()

@app.get("/health")
async def health_check():
    return {"status": "healthy", "service": "chatbot"}

@app.post("/api/v1/chat", response_model=ChatResponse)
async def chat(
    request: ChatRequest,
    authorization: str = Header(..., alias="Authorization")
):
    """Handle chatbot message"""
    try:
        # Verify API key
        if not verify_api_key(authorization):
            raise HTTPException(status_code=401, detail="Unauthorized")
        
        # Detect intent
        intent = intent_detector.detect(request.message)
        
        # Handle special intents
        if intent == "check_status":
            return ChatResponse(
                reply="Untuk melihat status pengajuan, silakan login terlebih dahulu.",
                links=[{"text": "Login", "url": "/user/login"}],
                requires_login=True
            )
        
        if intent == "list_services":
            return ChatResponse(
                reply="Layanan yang tersedia: SKTM, SKU, Surat Kelahiran, dll.",
                links=[],
                requires_login=False
            )
        
        # Build context
        context = context_builder.build(
            user_id=request.user_id,
            intent=intent
        )
        
        # Generate AI response
        ai_response = await ai_service.generate_response(
            message=request.message,
            context=context,
            conversation_history=request.conversation_history or []
        )
        
        return ChatResponse(
            reply=ai_response["message"],
            links=ai_response.get("links", []),
            requires_login=False
        )
        
    except Exception as e:
        logging.error(f"Chatbot error: {str(e)}")
        raise HTTPException(
            status_code=500,
            detail="Internal server error"
        )

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host=settings.HOST, port=settings.PORT)
```

### **2. Python Request Model (app/models/request.py)**

```python
from pydantic import BaseModel, Field
from typing import Optional, List, Dict

class ChatRequest(BaseModel):
    message: str = Field(..., min_length=1, max_length=2000)
    conversation_history: Optional[List[Dict[str, str]]] = []
    user_id: Optional[int] = None
    context: Optional[Dict] = None
    
    class Config:
        json_schema_extra = {
            "example": {
                "message": "Saya ingin membuat surat keterangan tidak mampu",
                "conversation_history": [],
                "user_id": 123
            }
        }
```

### **3. Python Response Model (app/models/response.py)**

```python
from pydantic import BaseModel
from typing import List, Dict, Optional

class Link(BaseModel):
    text: str
    url: str

class ChatResponse(BaseModel):
    reply: str
    links: List[Link] = []
    requires_login: bool = False
    
    class Config:
        json_schema_extra = {
            "example": {
                "reply": "Untuk membuat surat keterangan tidak mampu...",
                "links": [
                    {"text": "Formulir SKTM", "url": "/user/pengajuan/form/tidak-mampu"}
                ],
                "requires_login": False
            }
        }
```

### **4. Python AI Service (app/services/ai_service.py)**

```python
import httpx
import os
from typing import List, Dict
import logging

class AIService:
    def __init__(self):
        self.api_key = os.getenv("GROQ_API_KEY")
        self.base_url = os.getenv("GROQ_BASE_URL", "https://api.groq.com/openai/v1")
        self.model = os.getenv("GROQ_MODEL", "llama-3.3-70b-versatile")
    
    async def generate_response(
        self,
        message: str,
        context: str,
        conversation_history: List[Dict[str, str]] = None
    ) -> Dict:
        """Generate AI response using Groq API"""
        try:
            # Build messages
            messages = [
                {"role": "system", "content": context}
            ]
            
            # Add conversation history
            if conversation_history:
                messages.extend(conversation_history)
            
            # Add current message
            messages.append({"role": "user", "content": message})
            
            # Call Groq API
            async with httpx.AsyncClient(timeout=30.0) as client:
                response = await client.post(
                    f"{self.base_url}/chat/completions",
                    headers={
                        "Authorization": f"Bearer {self.api_key}",
                        "Content-Type": "application/json"
                    },
                    json={
                        "model": self.model,
                        "messages": messages,
                        "temperature": 0.7,
                        "max_tokens": 500
                    }
                )
                response.raise_for_status()
                data = response.json()
                
                return {
                    "message": data["choices"][0]["message"]["content"],
                    "links": []
                }
                
        except Exception as e:
            logging.error(f"AI Service error: {str(e)}")
            return {
                "message": "Mohon maaf, sistem sedang mengalami kendala.",
                "links": []
            }
```

### **5. Laravel Python Client (app/Chatbot/Services/PythonChatbotClient.php)**

```php
<?php

namespace App\Chatbot\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PythonChatbotClient
{
    protected $baseUrl;
    protected $apiKey;
    protected $timeout;

    public function __construct()
    {
        $this->baseUrl = config('chatbot.python.url');
        $this->apiKey = config('chatbot.python.api_key');
        $this->timeout = config('chatbot.python.timeout', 30);
    }

    /**
     * Send message to Python chatbot service
     */
    public function sendMessage(
        string $message,
        array $conversationHistory = [],
        ?int $userId = null,
        array $context = []
    ): array {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->baseUrl . '/api/v1/chat', [
                    'message' => $message,
                    'conversation_history' => $conversationHistory,
                    'user_id' => $userId,
                    'context' => $context,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            // Fallback jika Python service down
            Log::warning('Python chatbot service unavailable', [
                'status' => $response->status(),
                'error' => $response->body(),
            ]);

            return $this->getFallbackResponse($message);

        } catch (\Exception $e) {
            Log::error('Python chatbot client error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->getFallbackResponse($message);
        }
    }

    /**
     * Check Python service health
     */
    public function checkHealth(): bool
    {
        try {
            $response = Http::timeout(5)
                ->get($this->baseUrl . '/health');

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Fallback response jika Python service down
     */
    protected function getFallbackResponse(string $message): array
    {
        // Simple keyword matching sebagai fallback
        $lowerMessage = strtolower($message);
        
        if (strpos($lowerMessage, 'status') !== false) {
            return [
                'reply' => 'Untuk melihat status pengajuan, silakan login terlebih dahulu.',
                'links' => [
                    ['text' => 'Login', 'url' => route('user.login')]
                ],
                'requires_login' => true,
            ];
        }

        return [
            'reply' => 'Mohon maaf, sistem sedang mengalami kendala. Silakan coba beberapa saat lagi atau hubungi kantor kelurahan.',
            'links' => [],
            'requires_login' => false,
        ];
    }
}
```

### **6. Update Laravel ChatbotService (app/Chatbot/Services/ChatbotService.php)**

```php
// Update constructor
public function __construct(PythonChatbotClient $pythonClient)
{
    $this->pythonClient = $pythonClient;
    // ... existing code
}

// Update processMessage
public function processMessage(string $message, array $conversationHistory = [], ?int $userId = null): array
{
    try {
        // Check if Python service is available
        if ($this->pythonClient->checkHealth()) {
            // Use Python service
            return $this->pythonClient->sendMessage($message, $conversationHistory, $userId);
        }

        // Fallback to PHP implementation
        return $this->processMessagePHP($message, $conversationHistory, $userId);
        
    } catch (\Exception $e) {
        Log::error('Chatbot Service Error', [
            'message' => $e->getMessage(),
        ]);

        return [
            'success' => false,
            'reply' => 'Mohon maaf, sistem sedang mengalami kendala.',
            'links' => [],
            'requires_login' => false,
        ];
    }
}
```

### **7. Laravel Config (config/chatbot.php)**

```php
<?php

return [
    'python' => [
        'url' => env('PYTHON_CHATBOT_URL', 'http://localhost:8001'),
        'api_key' => env('PYTHON_CHATBOT_API_KEY'),
        'timeout' => env('PYTHON_CHATBOT_TIMEOUT', 30),
        'enabled' => env('PYTHON_CHATBOT_ENABLED', true),
    ],
];
```

### **8. Python Requirements (requirements.txt)**

```txt
fastapi==0.104.1
uvicorn[standard]==0.24.0
pydantic==2.5.0
httpx==0.25.1
python-dotenv==1.0.0
python-multipart==0.0.6
```

---

## 🔒 Keamanan

### **1. API Key Authentication**
- Python service memverifikasi API key dari Laravel
- Laravel menyimpan API key di `.env`

### **2. CORS Protection**
- Python service hanya menerima request dari origin yang diizinkan
- Configure di `ALLOWED_ORIGINS`

### **3. Rate Limiting** (Optional)
```python
from slowapi import Limiter, _rate_limit_exceeded_handler
from slowapi.util import get_remote_address

limiter = Limiter(key_func=get_remote_address)
app.state.limiter = limiter

@app.post("/api/v1/chat")
@limiter.limit("10/minute")
async def chat(...):
    ...
```

### **4. Input Validation**
- Pydantic models untuk validasi request
- Laravel validation sebelum forward ke Python

---

## 🚢 Deployment

### **1. Docker (Recommended)**

**Dockerfile (Python)**
```dockerfile
FROM python:3.11-slim

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8000"]
```

**docker-compose.yml**
```yaml
version: '3.8'

services:
  laravel:
    build: .
    ports:
      - "8000:8000"
    environment:
      - PYTHON_CHATBOT_URL=http://python-chatbot:8000
  
  python-chatbot:
    build: ./chatbot-python
    ports:
      - "8001:8000"
    environment:
      - GROQ_API_KEY=${GROQ_API_KEY}
      - API_KEY=${PYTHON_CHATBOT_API_KEY}
```

### **2. Production Checklist**
- [ ] Set `DEBUG=False` di Python
- [ ] Gunakan HTTPS
- [ ] Setup reverse proxy (Nginx)
- [ ] Enable logging
- [ ] Setup monitoring (Sentry, etc.)
- [ ] Configure rate limiting
- [ ] Setup health check endpoint

---

## 📊 Monitoring

### **Health Check Endpoint**
```python
@app.get("/health")
async def health():
    return {
        "status": "healthy",
        "service": "chatbot",
        "version": "1.0.0"
    }
```

### **Laravel Health Check**
```php
// routes/web.php
Route::get('/api/chatbot/health', function() {
    $pythonClient = app(\App\Chatbot\Services\PythonChatbotClient::class);
    return response()->json([
        'laravel' => 'healthy',
        'python' => $pythonClient->checkHealth() ? 'healthy' : 'unhealthy'
    ]);
});
```

---

## ✅ Checklist Migrasi

- [ ] Setup Python service
- [ ] Install dependencies
- [ ] Configure environment variables
- [ ] Create PythonChatbotClient di Laravel
- [ ] Update ChatbotService untuk use Python client
- [ ] Test endpoint
- [ ] Setup fallback mechanism
- [ ] Deploy Python service
- [ ] Monitor logs
- [ ] Test production

---

**Selamat migrasi! 🚀**


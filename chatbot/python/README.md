# Python Chatbot Service

FastAPI-based chatbot service for Laravel application.

## Setup

1. **Install dependencies:**
```bash
pip install -r requirements.txt
```

2. **Configure environment:**
```bash
cp .env.example .env
# Edit .env with your configuration
```

3. **Run service:**
```bash
uvicorn app.main:app --host 0.0.0.0 --port 8000 --reload
```

## API Endpoints

### Health Check
```
GET /health
```

### Chat
```
POST /api/v1/chat
Authorization: Bearer <api_key>
Content-Type: application/json

{
    "message": "Saya ingin membuat surat keterangan tidak mampu",
    "conversation_history": [],
    "user_id": 123
}
```

## Docker

```bash
docker build -t chatbot-python .
docker run -p 8000:8000 --env-file .env chatbot-python
```


"""
FastAPI Chatbot Service
Main entry point for Python chatbot service
"""
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

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Initialize FastAPI app
app = FastAPI(
    title="Chatbot API",
    version="1.0.0",
    description="Python-based chatbot service for Laravel application"
)

# CORS Middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=settings.ALLOWED_ORIGINS,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Initialize services
ai_service = AIService()
intent_detector = IntentDetector()
context_builder = ContextBuilder()


@app.get("/health")
async def health_check():
    """Health check endpoint"""
    return {
        "status": "healthy",
        "service": "chatbot",
        "version": "1.0.0"
    }


@app.post("/api/v1/chat", response_model=ChatResponse)
async def chat(
    request: ChatRequest,
    authorization: str = Header(..., alias="Authorization")
):
    """
    Handle chatbot message
    
    Args:
        request: Chat request with message and conversation history
        authorization: Bearer token for API authentication
    
    Returns:
        ChatResponse with reply, links, and requires_login flag
    """
    try:
        # Verify API key
        if not verify_api_key(authorization):
            logger.warning("Unauthorized access attempt")
            raise HTTPException(status_code=401, detail="Unauthorized")
        
        logger.info(f"Processing message from user {request.user_id}: {request.message[:50]}...")
        
        # Detect intent
        intent = intent_detector.detect(request.message)
        logger.info(f"Detected intent: {intent}")
        
        # Handle special intents
        if intent == "check_status":
            return ChatResponse(
                reply="Untuk melihat status pengajuan surat, silakan login terlebih dahulu.",
                links=[{"text": "Login", "url": "/user/login"}],
                requires_login=True
            )
        
        if intent == "list_services":
            services = [
                {"text": "Surat Keterangan Tidak Mampu", "url": "/user/pengajuan/form/tidak-mampu"},
                {"text": "Surat Keterangan Usaha", "url": "/user/pengajuan/form/usaha"},
                {"text": "Surat Keterangan Kelahiran", "url": "/user/pengajuan/form/kelahiran"},
                {"text": "Surat Keterangan Kematian", "url": "/user/pengajuan/form/kematian"},
            ]
            return ChatResponse(
                reply="Layanan yang tersedia:\n1. Surat Keterangan Tidak Mampu (SKTM)\n2. Surat Keterangan Usaha (SKU)\n3. Surat Keterangan Kelahiran\n4. Surat Keterangan Kematian\n\nKlik link di bawah untuk mengajukan surat.",
                links=services,
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
        
        logger.info("AI response generated successfully")
        
        return ChatResponse(
            reply=ai_response["message"],
            links=ai_response.get("links", []),
            requires_login=False
        )
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Chatbot error: {str(e)}", exc_info=True)
        raise HTTPException(
            status_code=500,
            detail="Internal server error"
        )


if __name__ == "__main__":
    import uvicorn
    uvicorn.run(
        app,
        host=settings.HOST,
        port=settings.PORT,
        log_level="info"
    )


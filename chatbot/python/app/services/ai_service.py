"""
AI Service for generating chatbot responses
"""
import httpx
import os
from typing import List, Dict
import logging
from app.config import settings

logger = logging.getLogger(__name__)


class AIService:
    """Service for interacting with AI API (Groq/OpenAI)"""
    
    def __init__(self):
        self.api_key = settings.GROQ_API_KEY
        self.base_url = settings.GROQ_BASE_URL
        self.model = settings.GROQ_MODEL
    
    async def generate_response(
        self,
        message: str,
        context: str,
        conversation_history: List[Dict[str, str]] = None
    ) -> Dict:
        """
        Generate AI response using Groq API
        
        Args:
            message: User message
            context: System context/prompt
            conversation_history: Previous conversation messages
        
        Returns:
            Dict with 'message' and 'links' keys
        """
        try:
            # Build messages array
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
                
                ai_message = data["choices"][0]["message"]["content"]
                
                logger.info("AI response generated successfully")
                
                return {
                    "message": ai_message,
                    "links": []
                }
                
        except httpx.HTTPError as e:
            logger.error(f"HTTP error calling AI API: {str(e)}")
            return {
                "message": "Mohon maaf, sistem sedang mengalami kendala. Silakan coba beberapa saat lagi.",
                "links": []
            }
        except Exception as e:
            logger.error(f"AI Service error: {str(e)}", exc_info=True)
            return {
                "message": "Mohon maaf, sistem sedang mengalami kendala.",
                "links": []
            }


"""
Request models for chatbot API
"""
from pydantic import BaseModel, Field
from typing import Optional, List, Dict


class ChatRequest(BaseModel):
    """Chat request model"""
    message: str = Field(..., min_length=1, max_length=2000, description="User message")
    conversation_history: Optional[List[Dict[str, str]]] = Field(default=[], description="Previous conversation messages")
    user_id: Optional[int] = Field(default=None, description="User ID if logged in")
    context: Optional[Dict] = Field(default=None, description="Additional context")
    
    class Config:
        json_schema_extra = {
            "example": {
                "message": "Saya ingin membuat surat keterangan tidak mampu",
                "conversation_history": [],
                "user_id": 123
            }
        }


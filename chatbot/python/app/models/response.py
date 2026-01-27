"""
Response models for chatbot API
"""
from pydantic import BaseModel
from typing import List, Optional


class Link(BaseModel):
    """Link model for response"""
    text: str
    url: str


class ChatResponse(BaseModel):
    """Chat response model"""
    reply: str
    links: List[Link] = []
    requires_login: bool = False
    
    class Config:
        json_schema_extra = {
            "example": {
                "reply": "Untuk membuat surat keterangan tidak mampu, silakan isi formulir di link berikut.",
                "links": [
                    {"text": "Formulir SKTM", "url": "/user/pengajuan/form/tidak-mampu"}
                ],
                "requires_login": False
            }
        }


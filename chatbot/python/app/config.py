"""
Configuration settings for Python chatbot service
"""
from pydantic_settings import BaseSettings
from typing import List


class Settings(BaseSettings):
    # Server
    HOST: str = "0.0.0.0"
    PORT: int = 8000
    DEBUG: bool = False
    
    # Security
    API_KEY: str
    ALLOWED_ORIGINS: List[str] = ["http://localhost", "http://localhost:8000"]
    
    # AI API
    GROQ_API_KEY: str
    GROQ_BASE_URL: str = "https://api.groq.com/openai/v1"
    GROQ_MODEL: str = "llama-3.3-70b-versatile"
    
    # Laravel Integration
    LARAVEL_URL: str = "http://localhost:8000"
    
    class Config:
        env_file = ".env"
        case_sensitive = True


settings = Settings()


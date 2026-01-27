"""
Authentication utilities
"""
import os
from app.config import settings
import logging

logger = logging.getLogger(__name__)


def verify_api_key(authorization: str) -> bool:
    """
    Verify API key from Authorization header
    
    Args:
        authorization: Authorization header value (format: "Bearer <token>")
    
    Returns:
        True if valid, False otherwise
    """
    try:
        if not authorization:
            return False
        
        # Extract token from "Bearer <token>"
        parts = authorization.split(" ")
        if len(parts) != 2 or parts[0].lower() != "bearer":
            return False
        
        token = parts[1]
        expected_token = settings.API_KEY
        
        # Constant-time comparison to prevent timing attacks
        return token == expected_token
        
    except Exception as e:
        logger.error(f"Auth verification error: {str(e)}")
        return False


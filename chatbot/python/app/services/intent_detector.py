"""
Intent detection service
"""
import re
from typing import Optional
import logging

logger = logging.getLogger(__name__)


class IntentDetector:
    """Detect user intent from message"""
    
    def __init__(self):
        # Intent patterns
        self.patterns = {
            "check_status": [
                r"status",
                r"cek.*status",
                r"bagaimana.*status",
                r"status.*pengajuan",
                r"status.*surat"
            ],
            "list_services": [
                r"layanan",
                r"surat.*apa.*saja",
                r"ada.*surat.*apa",
                r"daftar.*layanan",
                r"jenis.*surat"
            ],
            "sktm": [
                r"sktm",
                r"surat.*tidak.*mampu",
                r"keterangan.*tidak.*mampu"
            ],
            "sku": [
                r"sku",
                r"surat.*usaha",
                r"keterangan.*usaha"
            ]
        }
    
    def detect(self, message: str) -> str:
        """
        Detect intent from message
        
        Args:
            message: User message
        
        Returns:
            Detected intent string
        """
        message_lower = message.lower()
        
        # Check each intent pattern
        for intent, patterns in self.patterns.items():
            for pattern in patterns:
                if re.search(pattern, message_lower, re.IGNORECASE):
                    logger.info(f"Intent detected: {intent}")
                    return intent
        
        # Default: general inquiry
        return "general"


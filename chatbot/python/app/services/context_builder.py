"""
Context builder for AI prompts
"""
from typing import Optional


class ContextBuilder:
    """Build context for AI prompts"""
    
    def build(self, user_id: Optional[int] = None, intent: str = "general") -> str:
        """
        Build context string for AI
        
        Args:
            user_id: User ID if logged in
            intent: Detected intent
        
        Returns:
            Context string
        """
        base_context = """Anda adalah asisten virtual untuk Kelurahan Pabuaran Mekar.
Tugas Anda adalah membantu warga dalam mengajukan surat-surat kelurahan.

Layanan yang tersedia:
1. Surat Keterangan Tidak Mampu (SKTM) - untuk keringanan biaya pendidikan, kesehatan, dll
2. Surat Keterangan Usaha (SKU) - untuk usaha mikro dan kecil
3. Surat Keterangan Kelahiran - untuk pengurusan Akta Kelahiran
4. Surat Keterangan Kematian - untuk pengurusan Akta Kematian
5. Surat Pengantar PBB - untuk pengurusan pajak bumi dan bangunan

Jawab dengan ramah, jelas, dan informatif dalam bahasa Indonesia.
Jika user bertanya tentang layanan tertentu, berikan informasi lengkap dan link ke formulir.
"""
        
        if user_id:
            base_context += f"\nUser ID: {user_id} (sudah login)"
        else:
            base_context += "\nUser belum login. Jika perlu login, sarankan untuk login terlebih dahulu."
        
        return base_context


<?php

namespace App\Chatbot\Prompts;

class SystemPrompt
{
    /**
     * Get system prompt
     */
    public function getPrompt(): string
    {
        $promptPath = app_path('Chatbot/Prompts/system_prompt.txt');
        
        if (file_exists($promptPath)) {
            return file_get_contents($promptPath);
        }

        return $this->getDefaultPrompt();
    }

    /**
     * Get additional context
     */
    public function getContext(?array $serviceLink = null): string
    {
        $context = "";

        if ($serviceLink) {
            $context .= "User sedang bertanya tentang: {$serviceLink['text']}\n";
            $context .= "Link layanan: {$serviceLink['url']}\n";
        }

        return $context;
    }

    /**
     * Default prompt
     */
    private function getDefaultPrompt(): string
    {
        return "Anda adalah asisten virtual resmi Kelurahan Pabuaran Mekar.\n\n" .
               "Gunakan bahasa Indonesia yang sopan, informatif, singkat, dan formal ringan (gaya pemerintahan).\n\n" .
               "Hanya jawab pertanyaan terkait layanan kelurahan. Jika di luar konteks, tolak dengan sopan.";
    }
}


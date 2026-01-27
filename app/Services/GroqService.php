<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected $apiKey;
    protected $baseUrl;
    protected $model;

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
        $this->baseUrl = config('services.groq.base_url');
        $this->model = config('services.groq.model');
    }

    /**
     * Send chat message to Groq API
     *
     * @param string $message
     * @param array $conversationHistory
     * @param array $systemPrompt
     * @return array
     */
    public function chat(string $message, array $conversationHistory = [], array $systemPrompt = []): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'error' => 'Groq API key tidak ditemukan. Pastikan GROQ_API_KEY sudah diset di file .env',
            ];
        }

        try {
            // Prepare messages
            $messages = [];

            // Add system prompt if provided
            if (!empty($systemPrompt)) {
                $messages[] = [
                    'role' => 'system',
                    'content' => is_array($systemPrompt) ? implode("\n", $systemPrompt) : $systemPrompt,
                ];
            }

            // Add conversation history
            foreach ($conversationHistory as $history) {
                if (isset($history['role']) && isset($history['content'])) {
                    $messages[] = [
                        'role' => $history['role'],
                        'content' => $history['content'],
                    ];
                }
            }

            // Add current user message
            $messages[] = [
                'role' => 'user',
                'content' => $message,
            ];

            // Make API request
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("{$this->baseUrl}/chat/completions", [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 1024,
                'top_p' => 1,
                'stream' => false,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Check if response has choices
                if (!isset($data['choices']) || empty($data['choices'])) {
                    Log::error('Groq API: No choices in response', ['data' => $data]);
                    return [
                        'success' => false,
                        'error' => 'Tidak ada respons dari API. Silakan coba lagi.',
                    ];
                }

                return [
                    'success' => true,
                    'message' => $data['choices'][0]['message']['content'] ?? 'Tidak ada respons dari chatbot.',
                    'usage' => $data['usage'] ?? null,
                    'model' => $data['model'] ?? $this->model,
                ];
            }

            // Log error response
            $errorData = $response->json();
            $errorMessage = $errorData['error']['message'] ?? 'Terjadi kesalahan saat menghubungi Groq API.';
            
            Log::error('Groq API Error Response', [
                'status' => $response->status(),
                'error' => $errorMessage,
                'response' => $errorData,
            ]);

            return [
                'success' => false,
                'error' => $errorMessage,
                'status_code' => $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('Groq API Error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Generate system prompt for Kelurahan chatbot
     * Reads from resources/chatbot_prompt.txt file
     *
     * @return string
     */
    public function getKelurahanSystemPrompt(): string
    {
        $promptPath = resource_path('chatbot_prompt.txt');
        
        if (file_exists($promptPath)) {
            return file_get_contents($promptPath);
        }

        // Fallback prompt if file doesn't exist
        return "Anda adalah asisten virtual yang membantu warga Kelurahan Pabuaran Mekar, Kecamatan Cibinong. 
Tugas Anda adalah menjawab pertanyaan terkait layanan administrasi kelurahan, pengajuan surat, dan informasi umum.

Layanan yang tersedia:
1. Surat Kelahiran
2. Surat Kematian
3. Surat Keterangan Usaha
4. Surat Keterangan Domisili Usaha
5. Pengantar PBB

Jawab pertanyaan dengan ramah, jelas, dan informatif. Jika tidak tahu, arahkan pengguna untuk menghubungi kelurahan atau mengakses halaman pengajuan surat online.";
    }
}


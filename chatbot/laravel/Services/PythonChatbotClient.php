<?php

namespace App\Chatbot\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PythonChatbotClient
{
    protected $baseUrl;
    protected $apiKey;
    protected $timeout;
    protected $enabled;

    public function __construct()
    {
        $this->baseUrl = config('chatbot.python.url');
        $this->apiKey = config('chatbot.python.api_key');
        $this->timeout = config('chatbot.python.timeout', 30);
        $this->enabled = config('chatbot.python.enabled', true);
    }

    /**
     * Send message to Python chatbot service
     *
     * @param string $message
     * @param array $conversationHistory
     * @param string|null $userId MongoDB ObjectId as string
     * @param array $context
     * @return array
     */
    public function sendMessage(
        string $message,
        array $conversationHistory = [],
        ?string $userId = null,
        array $context = []
    ): array {
        if (!$this->enabled) {
            Log::info('Python chatbot service is disabled');
            return $this->getFallbackResponse($message);
        }

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->baseUrl . '/api/v1/chat', [
                    'message' => $message,
                    'conversation_history' => $conversationHistory,
                    'user_id' => $userId,
                    'context' => $context,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Ensure response format is correct
                return [
                    'reply' => $data['reply'] ?? 'Mohon maaf, terjadi kesalahan.',
                    'links' => $data['links'] ?? [],
                    'requires_login' => $data['requires_login'] ?? false,
                ];
            }

            // Log error and return fallback
            Log::warning('Python chatbot service returned error', [
                'status' => $response->status(),
                'error' => $response->body(),
            ]);

            return $this->getFallbackResponse($message);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Python chatbot service connection error', [
                'message' => $e->getMessage(),
            ]);

            return $this->getFallbackResponse($message);

        } catch (\Exception $e) {
            Log::error('Python chatbot client error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->getFallbackResponse($message);
        }
    }

    /**
     * Check Python service health
     *
     * @return bool
     */
    public function checkHealth(): bool
    {
        try {
            $response = Http::timeout(5)
                ->get($this->baseUrl . '/health');

            return $response->successful() && 
                   ($response->json()['status'] ?? '') === 'healthy';
        } catch (\Exception $e) {
            Log::debug('Python chatbot health check failed', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Fallback response jika Python service down
     *
     * @param string $message
     * @return array
     */
    protected function getFallbackResponse(string $message): array
    {
        // Simple keyword matching sebagai fallback
        $lowerMessage = strtolower($message);
        
        if (strpos($lowerMessage, 'status') !== false) {
            return [
                'reply' => 'Untuk melihat status pengajuan surat, silakan login terlebih dahulu.',
                'links' => [
                    ['text' => 'Login', 'url' => route('user.login')]
                ],
                'requires_login' => true,
            ];
        }

        if (strpos($lowerMessage, 'layanan') !== false || strpos($lowerMessage, 'surat') !== false) {
            return [
                'reply' => 'Layanan yang tersedia:\n1. Surat Keterangan Tidak Mampu (SKTM)\n2. Surat Keterangan Usaha (SKU)\n3. Surat Keterangan Kelahiran\n4. Surat Keterangan Kematian\n\nSilakan pilih layanan yang ingin Anda ajukan.',
                'links' => [
                    ['text' => 'Formulir SKTM', 'url' => route('user.pengajuan.form', ['jenis' => 'tidak-mampu'])],
                    ['text' => 'Formulir SKU', 'url' => route('user.pengajuan.form', ['jenis' => 'usaha'])],
                ],
                'requires_login' => false,
            ];
        }

        return [
            'reply' => 'Mohon maaf, sistem sedang mengalami kendala. Silakan coba beberapa saat lagi atau hubungi kantor kelurahan untuk informasi lebih lanjut.',
            'links' => [],
            'requires_login' => false,
        ];
    }
}


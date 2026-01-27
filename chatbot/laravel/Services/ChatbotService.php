<?php

namespace App\Chatbot\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\PengajuanSurat;
use App\Chatbot\Prompts\SystemPrompt;
use App\Chatbot\Helpers\IntentDetector;

class ChatbotService
{
    protected $apiKey;
    protected $baseUrl;
    protected $model;
    protected $systemPrompt;
    protected $intentDetector;
    protected $pythonClient;

    public function __construct(PythonChatbotClient $pythonClient = null)
    {
        $this->apiKey = env('AI_API_KEY') ?: env('GROQ_API_KEY');
        $this->baseUrl = env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1');
        $this->model = env('GROQ_MODEL', 'llama-3.3-70b-versatile');
        $this->systemPrompt = new SystemPrompt();
        $this->intentDetector = new IntentDetector();
        $this->pythonClient = $pythonClient ?? app(PythonChatbotClient::class);
    }

    /**
     * Process chat message
     * Try Python service first, fallback to PHP if unavailable
     */
    public function processMessage(string $message, array $conversationHistory = [], ?string $userId = null): array
    {
        try {
            // Check if Python service is enabled and available
            $pythonEnabled = config('chatbot.python.enabled', true);
            $pythonHealthy = false;
            
            if ($pythonEnabled) {
                try {
                    $pythonHealthy = $this->pythonClient->checkHealth();
                } catch (\Exception $e) {
                    Log::debug('Python chatbot health check failed', [
                        'message' => $e->getMessage(),
                    ]);
                    $pythonHealthy = false;
                }
            }
            
            if ($pythonEnabled && $pythonHealthy) {
                // Use Python service
                Log::info('Using Python chatbot service', ['userId' => $userId]);
                try {
                    return $this->pythonClient->sendMessage($message, $conversationHistory, $userId);
                } catch (\Exception $e) {
                    Log::warning('Python chatbot service failed, falling back to PHP', [
                        'message' => $e->getMessage(),
                    ]);
                    // Fall through to PHP fallback
                }
            }

            // Fallback to PHP implementation
            Log::info('Using PHP chatbot service (fallback)', ['userId' => $userId]);
            return $this->processMessagePHP($message, $conversationHistory, $userId);

        } catch (\Exception $e) {
            Log::error('Chatbot Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'userId' => $userId,
            ]);

            // Return fallback response instead of error
            return [
                'success' => false,
                'reply' => 'Mohon maaf, sistem sedang mengalami kendala. Silakan coba beberapa saat lagi atau hubungi kantor kelurahan untuk informasi lebih lanjut.',
                'links' => [],
                'requires_login' => false,
            ];
        }
    }

    /**
     * Process message using PHP implementation (fallback)
     */
    protected function processMessagePHP(string $message, array $conversationHistory = [], ?string $userId = null): array
    {
        try {
            // Detect intent
            $intent = $this->intentDetector->detect($message);

            // Handle status check
            if ($intent === 'check_status') {
                return $this->handleStatusCheck($userId);
            }

            // Handle services list
            if ($intent === 'list_services') {
                return $this->handleServicesList();
            }

            // Get service link if asking about specific service
            $serviceLink = $this->intentDetector->getServiceLink($message);

            // Prepare context
            $context = $this->systemPrompt->getContext($serviceLink);

            // Send to AI
            $aiResponse = $this->callAI($message, $conversationHistory, $context);

            $reply = $aiResponse['message'] ?? '';
            
            // Ensure reply is not empty
            if (empty(trim($reply))) {
                $reply = 'Terima kasih atas pertanyaan Anda. Silakan pilih layanan yang ingin Anda ajukan atau hubungi kantor kelurahan untuk informasi lebih lanjut.';
            }

            return [
                'success' => true,
                'reply' => $reply,
                'links' => $serviceLink ? [$serviceLink] : [],
                'requires_login' => false,
            ];

        } catch (\Exception $e) {
            Log::error('Chatbot PHP Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'reply' => 'Mohon maaf, sistem sedang mengalami kendala. Silakan coba beberapa saat lagi.',
                'links' => [],
                'requires_login' => false,
            ];
        }
    }

    /**
     * Handle status check request
     */
    private function handleStatusCheck(?string $userId): array
    {
        if (!$userId) {
            return [
                'success' => true,
                'reply' => 'Untuk melihat status pengajuan surat, silakan login terlebih dahulu.',
                'links' => [
                    [
                        'text' => 'Login',
                        'url' => route('user.login'),
                    ],
                ],
                'requires_login' => true,
            ];
        }

        try {
            $pengajuan = PengajuanSurat::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$pengajuan) {
                return [
                    'success' => true,
                    'reply' => 'Anda belum memiliki pengajuan surat. Silakan ajukan layanan terlebih dahulu.',
                    'links' => [],
                    'requires_login' => false,
                ];
            }

            $statusText = match($pengajuan->status) {
                PengajuanSurat::STATUS_MENUNGGU => 'Menunggu verifikasi',
                PengajuanSurat::STATUS_DIPROSES => 'Sedang diproses',
                PengajuanSurat::STATUS_REVISI => 'Perlu revisi',
                PengajuanSurat::STATUS_SELESAI => 'Selesai',
                default => 'Tidak diketahui',
            };

            $reply = "Status pengajuan terakhir Anda:\n\n";
            $reply .= "Jenis Layanan: {$pengajuan->jenis_layanan}\n";
            $reply .= "Status: {$statusText}\n";
            $reply .= "Tanggal Pengajuan: " . $pengajuan->created_at->format('d/m/Y') . "\n";

            if ($pengajuan->status === PengajuanSurat::STATUS_REVISI && $pengajuan->keterangan) {
                $reply .= "\nKeterangan: {$pengajuan->keterangan}";
            }

            if ($pengajuan->status === PengajuanSurat::STATUS_SELESAI) {
                $reply .= "\n\nSurat Anda sudah siap diambil di kantor kelurahan.";
            }

            // Tidak perlu menambahkan link "Lihat Status" karena user sudah di dashboard
            return [
                'success' => true,
                'reply' => $reply,
                'links' => [],
                'requires_login' => false,
            ];

        } catch (\Exception $e) {
            Log::error('Status Check Error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'reply' => 'Mohon maaf, sistem sedang mengalami kendala. Silakan coba beberapa saat lagi.',
                'links' => [],
                'requires_login' => false,
            ];
        }
    }

    /**
     * Handle services list request
     */
    private function handleServicesList(): array
    {
        $services = $this->getAvailableServices();
        
        $reply = "Selamat datang di Layanan Digital Kelurahan. Berikut layanan yang tersedia:\n\n";
        
        foreach ($services as $index => $service) {
            $reply .= ($index + 1) . ". {$service['name']}\n";
            $reply .= "   {$service['description']}\n\n";
        }

        $reply .= "Silakan pilih layanan yang ingin Anda ajukan.";

        $links = array_map(function($service) {
            return [
                'text' => $service['name'],
                'url' => $service['url'],
            ];
        }, $services);

        return [
            'success' => true,
            'reply' => $reply,
            'links' => $links,
            'requires_login' => false,
        ];
    }

    /**
     * Get available services (HANYA 5 LAYANAN)
     */
    public function getAvailableServices(): array
    {
        return [
            [
                'name' => 'Surat Keterangan Kelahiran',
                'slug' => 'kelahiran',
                'description' => 'Surat pengantar untuk pengurusan Akta Kelahiran',
                'url' => route('user.pengajuan.form', ['jenis' => 'kelahiran']),
            ],
            [
                'name' => 'Surat Keterangan Kematian',
                'slug' => 'kematian',
                'description' => 'Surat pengantar untuk pengurusan Akta Kematian',
                'url' => route('user.pengajuan.form', ['jenis' => 'kematian']),
            ],
            [
                'name' => 'Surat Keterangan Usaha',
                'slug' => 'usaha',
                'description' => 'Surat keterangan untuk usaha mikro dan kecil',
                'url' => route('user.pengajuan.form', ['jenis' => 'usaha']),
            ],
            [
                'name' => 'Surat Keterangan Tidak Mampu',
                'slug' => 'tidak-mampu',
                'description' => 'Surat keterangan tidak mampu untuk keringanan biaya pendidikan, kesehatan, dll',
                'url' => route('user.pengajuan.form', ['jenis' => 'tidak-mampu']),
            ],
            [
                'name' => 'Surat Pengantar PBB',
                'slug' => 'pbb',
                'description' => 'Surat pengantar untuk pengurusan pajak bumi dan bangunan',
                'url' => route('user.pengajuan.form', ['jenis' => 'pbb']),
            ],
        ];
    }

    /**
     * Call AI API
     */
    private function callAI(string $message, array $conversationHistory, string $context): array
    {
        if (empty($this->apiKey)) {
            throw new \Exception('API key tidak ditemukan');
        }

        $messages = [];

        // System prompt
        $systemPrompt = $this->systemPrompt->getPrompt();
        if ($context) {
            $systemPrompt .= "\n\n" . $context;
        }
        
        $messages[] = [
            'role' => 'system',
            'content' => $systemPrompt,
        ];

        // Conversation history
        foreach ($conversationHistory as $history) {
            if (isset($history['role']) && isset($history['content'])) {
                $messages[] = [
                    'role' => $history['role'],
                    'content' => $history['content'],
                ];
            }
        }

        // Current message
        $messages[] = [
            'role' => 'user',
            'content' => $message,
        ];

        /** @var \Illuminate\Http\Client\Response $response */
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

        if (!$response->successful()) {
            $errorData = $response->json();
            throw new \Exception($errorData['error']['message'] ?? 'API Error');
        }

        $data = $response->json();

        if (!isset($data['choices']) || empty($data['choices'])) {
            throw new \Exception('No response from API');
        }

        $message = $data['choices'][0]['message']['content'] ?? '';
        
        // Ensure message is not empty
        if (empty(trim($message))) {
            $message = 'Terima kasih atas pertanyaan Anda. Silakan pilih layanan yang ingin Anda ajukan atau hubungi kantor kelurahan untuk informasi lebih lanjut.';
        }

        return [
            'message' => $message,
        ];
    }
}


<?php

namespace App\Chatbot\Controllers;

use App\Chatbot\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatbotController extends \App\Http\Controllers\Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Handle chatbot message
     * POST /api/chatbot/message (Public)
     */
    public function message(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'conversation_history' => 'sometimes|array',
        ]);

        try {
            $message = trim($request->input('message'));
            $conversationHistory = $request->input('conversation_history', []);
            
            // Get user ID - bisa null untuk guest, atau ObjectId/string untuk user login
            $user = Auth::user();
            $userId = $user ? (string) $user->_id : null;

            $result = $this->chatbotService->processMessage($message, $conversationHistory, $userId);

            // Ensure reply is not empty
            $reply = $result['reply'] ?? 'Mohon maaf, sistem sedang mengalami kendala. Silakan coba beberapa saat lagi.';
            
            if (empty(trim($reply))) {
                $reply = 'Terima kasih atas pertanyaan Anda. Silakan pilih layanan yang ingin Anda ajukan atau hubungi kantor kelurahan untuk informasi lebih lanjut.';
            }

            return response()->json([
                'reply' => $reply,
                'links' => $result['links'] ?? [],
                'requires_login' => $result['requires_login'] ?? false,
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot Controller Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'is_authenticated' => Auth::check(),
            ]);

            return response()->json([
                'reply' => 'Mohon maaf, sistem sedang mengalami kendala. Silakan coba beberapa saat lagi atau hubungi kantor kelurahan untuk informasi lebih lanjut.',
                'links' => [],
                'requires_login' => false,
            ], 500);
        }
    }

    /**
     * Get chatbot status
     * GET /api/chatbot/status (Public)
     */
    public function status(): JsonResponse
    {
        $apiKey = env('AI_API_KEY') ?: env('GROQ_API_KEY');
        $isConfigured = !empty($apiKey);

        return response()->json([
            'status' => $isConfigured ? 'active' : 'inactive',
            'configured' => $isConfigured,
            'message' => $isConfigured 
                ? 'Chatbot siap digunakan' 
                : 'Chatbot belum dikonfigurasi',
        ]);
    }
}

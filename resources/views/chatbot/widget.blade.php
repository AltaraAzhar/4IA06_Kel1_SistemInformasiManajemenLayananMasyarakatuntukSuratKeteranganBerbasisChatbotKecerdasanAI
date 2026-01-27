<!-- Chatbot Widget -->
<div class="chatbot-container">
    <!-- Toggle Button -->
    <button id="chatbot-toggle" class="chatbot-toggle" aria-label="Buka Chatbot">
        <i class="fas fa-comments"></i>
        <span class="badge" style="display: none;">1</span>
    </button>

    <!-- Chatbot Window -->
    <div id="chatbot-window" class="chatbot-window">
        <!-- Header -->
        <div class="chatbot-header">
            <h3>
                <i class="fas fa-robot"></i>
                Chat Layanan Kelurahan
            </h3>
            <button id="chatbot-close" class="chatbot-close" aria-label="Tutup Chatbot">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Messages Container -->
        <div id="chatbot-messages" class="chatbot-messages">
            <!-- Messages will be dynamically added here -->
        </div>

        <!-- Input Container -->
        <div class="chatbot-input-container">
            <form id="chatbot-form">
                <textarea
                    id="chatbot-input"
                    class="chatbot-input"
                    placeholder="Ketik pesan Anda di sini..."
                    rows="1"
                    maxlength="2000"
                ></textarea>
                <button type="submit" id="chatbot-send" class="chatbot-send" aria-label="Kirim Pesan">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Chatbot CSS -->
<link rel="stylesheet" href="{{ asset('chatbot/css/chatbot.css') }}">

<!-- Chatbot JavaScript -->
<script src="{{ asset('chatbot/js/chatbot.js') }}"></script>


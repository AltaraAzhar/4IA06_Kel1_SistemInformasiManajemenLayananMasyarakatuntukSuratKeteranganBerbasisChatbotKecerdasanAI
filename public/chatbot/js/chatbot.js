/**
 * Chatbot JavaScript - Production Ready
 */
class Chatbot {
    constructor() {
        this.isOpen = false;
        this.conversationHistory = [];
        this.init();
    }

    init() {
        this.toggleButton = document.getElementById('chatbot-toggle');
        this.chatbotWindow = document.getElementById('chatbot-window');
        this.chatbotClose = document.getElementById('chatbot-close');
        this.messagesContainer = document.getElementById('chatbot-messages');
        this.inputField = document.getElementById('chatbot-input');
        this.sendButton = document.getElementById('chatbot-send');
        this.form = document.getElementById('chatbot-form');

        if (this.toggleButton) {
            this.toggleButton.addEventListener('click', () => this.toggle());
        }

        if (this.chatbotClose) {
            this.chatbotClose.addEventListener('click', () => this.close());
        }

        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        }

        if (this.inputField) {
            this.inputField.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.handleSubmit(e);
                }
            });

            this.inputField.addEventListener('input', () => {
                this.inputField.style.height = 'auto';
                this.inputField.style.height = Math.min(this.inputField.scrollHeight, 100) + 'px';
            });
        }

        this.showWelcomeMessage();
    }

    toggle() {
        this.isOpen = !this.isOpen;
        if (this.isOpen) {
            this.open();
        } else {
            this.close();
        }
    }

    open() {
        if (this.chatbotWindow) {
            this.chatbotWindow.classList.add('active');
            this.isOpen = true;
            setTimeout(() => {
                if (this.inputField) {
                    this.inputField.focus();
                }
            }, 300);
        }
    }

    close() {
        if (this.chatbotWindow) {
            this.chatbotWindow.classList.remove('active');
            this.isOpen = false;
        }
    }

    showWelcomeMessage() {
        if (!this.messagesContainer) return;

        const welcomeHTML = `
            <div class="chatbot-empty">
                <i class="fas fa-comments"></i>
                <p>Selamat datang di Layanan Digital Kelurahan.</p>
                <p style="margin-top: 8px; font-size: 12px;">Saya siap membantu Anda terkait layanan administrasi kelurahan.</p>
            </div>
        `;

        if (this.messagesContainer.children.length === 0) {
            this.messagesContainer.innerHTML = welcomeHTML;
        }
    }

    addMessage(content, isUser = false, links = []) {
        if (!this.messagesContainer) return;

        const emptyState = this.messagesContainer.querySelector('.chatbot-empty');
        if (emptyState) {
            emptyState.remove();
        }

        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;

        const time = new Date().toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });

        let formattedContent = this.formatMessage(content);
        
        let linksHtml = '';
        if (links && links.length > 0) {
            linksHtml = '<div class="chatbot-links">';
            links.forEach(link => {
                linksHtml += `<a href="${link.url}" class="chatbot-link">${this.escapeHtml(link.text)} <i class="fas fa-external-link-alt"></i></a>`;
            });
            linksHtml += '</div>';
        }

        messageDiv.innerHTML = `
            <div class="message-bubble">
                ${formattedContent}
                ${linksHtml}
            </div>
            <div class="message-time">${time}</div>
        `;

        this.messagesContainer.appendChild(messageDiv);
        this.scrollToBottom();
    }

    formatMessage(content) {
        let formatted = this.escapeHtml(content);
        formatted = formatted.replace(/\n/g, '<br>');
        formatted = formatted.replace(/(\d+)\.\s/g, '<strong>$1.</strong> ');
        return formatted;
    }

    addLoadingMessage() {
        if (!this.messagesContainer) return;

        const emptyState = this.messagesContainer.querySelector('.chatbot-empty');
        if (emptyState) {
            emptyState.remove();
        }

        const messageDiv = document.createElement('div');
        messageDiv.className = 'message bot';
        messageDiv.id = 'chatbot-loading';

        messageDiv.innerHTML = `
            <div class="message-loading">
                <span></span>
                <span></span>
                <span></span>
            </div>
        `;

        this.messagesContainer.appendChild(messageDiv);
        this.scrollToBottom();
    }

    removeLoadingMessage() {
        const loadingMessage = document.getElementById('chatbot-loading');
        if (loadingMessage) {
            loadingMessage.remove();
        }
    }

    async handleSubmit(e) {
        e.preventDefault();

        if (!this.inputField || !this.sendButton) return;

        const message = this.inputField.value.trim();
        if (!message) return;

        this.inputField.disabled = true;
        this.sendButton.disabled = true;

        this.addMessage(message, true);

        this.conversationHistory.push({
            role: 'user',
            content: message
        });

        this.inputField.value = '';
        this.inputField.style.height = 'auto';

        this.addLoadingMessage();

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const response = await fetch('/api/chatbot/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    conversation_history: this.conversationHistory
                })
            });

            if (!response.ok) {
                // Handle different error status codes
                if (response.status === 419) {
                    // CSRF token mismatch - reload page
                    throw new Error('Sesi telah berakhir. Silakan refresh halaman dan coba lagi.');
                } else if (response.status === 500) {
                    // Server error
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Terjadi kesalahan pada server.');
                } else {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
            }

            const data = await response.json();

            this.removeLoadingMessage();

            if (data.reply) {
                this.addMessage(data.reply, false, data.links || []);

                this.conversationHistory.push({
                    role: 'assistant',
                    content: data.reply
                });

                // Handle login requirement
                if (data.requires_login) {
                    setTimeout(() => {
                        if (confirm('Anda perlu login untuk melihat status pengajuan. Ingin login sekarang?')) {
                            window.location.href = '/user/login';
                        }
                    }, 500);
                }
            } else {
                this.addMessage('Mohon maaf, sistem sedang mengalami kendala. Silakan coba beberapa saat lagi.', false);
            }
        } catch (error) {
            this.removeLoadingMessage();
            const errorMessage = error.message || 'Mohon maaf, sistem sedang mengalami kendala. Silakan coba beberapa saat lagi.';
            this.addMessage(errorMessage, false);
            console.error('Chatbot error:', error);
            
            // If CSRF error, suggest reload
            if (error.message && error.message.includes('Sesi telah berakhir')) {
                setTimeout(() => {
                    if (confirm('Sesi Anda telah berakhir. Ingin me-refresh halaman?')) {
                        window.location.reload();
                    }
                }, 1000);
            }
        } finally {
            this.inputField.disabled = false;
            this.sendButton.disabled = false;
            this.inputField.focus();
        }
    }

    scrollToBottom() {
        if (this.messagesContainer) {
            this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
        }
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.chatbot = new Chatbot();
});


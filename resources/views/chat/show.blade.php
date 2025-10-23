@extends('layouts.app')

@section('title', 'Chat with ' . $user->name)

@section('content')
<style>
.chat-page-container {
    max-width: 900px;
    margin: 80px auto 40px;
    padding: 20px;
}

.chat-box {
    background: #020d18;
    border-radius: 8px;
    border: 1px solid #405266;
    display: flex;
    flex-direction: column;
    height: 600px;
}

.chat-box-header {
    display: flex;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #405266;
    gap: 15px;
}

.back-btn {
    background: #405266;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: #4a5d73;
}

.chat-user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.chat-user-info h3 {
    color: #fff;
    margin: 0 0 5px 0;
    font-size: 18px;
}

.chat-user-info p {
    color: #abb7c4;
    margin: 0;
    font-size: 14px;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.message {
    display: flex;
    gap: 10px;
    max-width: 70%;
}

.message.sent {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.message.received {
    align-self: flex-start;
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    flex-shrink: 0;
}

.message-content {
    background: #0b1a2a;
    padding: 12px 16px;
    border-radius: 12px;
    border: 1px solid #405266;
}

.message.sent .message-content {
    background: #4280bf;
    border-color: #4280bf;
}

.message-text {
    color: #fff;
    margin: 0 0 5px 0;
    line-height: 1.5;
    word-wrap: break-word;
}

.message-time {
    color: #abb7c4;
    font-size: 11px;
    display: block;
}

.message.sent .message-time {
    color: rgba(255, 255, 255, 0.7);
}

.chat-input-area {
    padding: 20px;
    border-top: 1px solid #405266;
    display: flex;
    gap: 10px;
}

.chat-input {
    flex: 1;
    background: #0b1a2a;
    border: 1px solid #405266;
    color: #fff;
    padding: 12px 16px;
    border-radius: 25px;
    font-size: 14px;
    outline: none;
    transition: all 0.3s ease;
}

.chat-input:focus {
    border-color: #e9d736;
}

.send-btn {
    background: #4280bf;
    color: #fff;
    border: none;
    padding: 12px 24px;
    border-radius: 25px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.send-btn:hover {
    background: #356ba8;
}

.send-btn:disabled {
    background: #405266;
    cursor: not-allowed;
}

.empty-chat {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #abb7c4;
    font-size: 16px;
}

/* Custom scrollbar for chat messages */
.chat-messages::-webkit-scrollbar {
    width: 8px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #0b1a2a;
    border-radius: 4px;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #405266;
    border-radius: 4px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #4a5d73;
}
</style>

<!-- Header -->
<header class="ht-header full-width-mr">
    <div class="container">
        @include('partials._header_top')
        @include('partials._search')
    </div>
</header>

<div class="chat-page-container">
    <div class="chat-box">
        <div class="chat-box-header">
            <button class="back-btn" onclick="window.location.href='{{ route('chat.index') }}'">
                <i class="ion-ios-arrow-left"></i> Back
            </button>
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="chat-user-avatar">
            <div class="chat-user-info">
                <h3>{{ $user->name }}</h3>
                <p>{{ '@' . $user->username }}</p>
            </div>
        </div>

        <div class="chat-messages" id="chatMessages">
            @if($messages->count() > 0)
                @foreach($messages as $message)
                    <div class="message {{ $message->sender_id == Auth::id() ? 'sent' : 'received' }}">
                        <img src="{{ $message->sender->avatar_url }}" alt="{{ $message->sender->name }}" class="message-avatar">
                        <div class="message-content">
                            <p class="message-text">{{ $message->message }}</p>
                            <span class="message-time">{{ $message->created_at->format('g:i A') }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-chat">
                    Start the conversation by sending a message!
                </div>
            @endif
        </div>

        <div class="chat-input-area">
            <input type="text" class="chat-input" id="messageInput" placeholder="Type a message..." maxlength="1000">
            <button class="send-btn" id="sendBtn" onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>

<script>
const receiverId = {{ $user->id }};
let isLoading = false;

// Auto-scroll to bottom on page load
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    
    // Focus on input
    document.getElementById('messageInput').focus();
    
    // Send message on Enter key
    document.getElementById('messageInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
    
    // Poll for new messages every 3 seconds
    setInterval(fetchMessages, 3000);
});

function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message || isLoading) {
        return;
    }
    
    isLoading = true;
    const sendBtn = document.getElementById('sendBtn');
    sendBtn.disabled = true;
    sendBtn.textContent = 'Sending...';
    
    fetch(`/messages/${receiverId}/send`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear input
            input.value = '';
            
            // Add message to chat
            appendMessage(data.message, true);
            
            // Scroll to bottom
            scrollToBottom();
        } else {
            alert(data.message || 'Failed to send message.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to send message. Please try again.');
    })
    .finally(() => {
        isLoading = false;
        sendBtn.disabled = false;
        sendBtn.textContent = 'Send';
        input.focus();
    });
}

function fetchMessages() {
    if (isLoading) return;
    
    fetch(`/messages/${receiverId}/fetch`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateMessages(data.messages);
        }
    })
    .catch(error => console.error('Error fetching messages:', error));
}

let lastMessageCount = {{ $messages->count() }};

function updateMessages(messages) {
    if (messages.length > lastMessageCount) {
        const chatMessages = document.getElementById('chatMessages');
        const shouldScroll = chatMessages.scrollHeight - chatMessages.scrollTop <= chatMessages.clientHeight + 100;
        
        // Clear and rebuild messages
        chatMessages.innerHTML = '';
        messages.forEach(message => {
            appendMessage(message, message.sender_id == {{ Auth::id() }});
        });
        
        lastMessageCount = messages.length;
        
        if (shouldScroll) {
            scrollToBottom();
        }
    }
}

function appendMessage(message, isSent) {
    const chatMessages = document.getElementById('chatMessages');
    
    // Remove empty chat message if it exists
    const emptyChat = chatMessages.querySelector('.empty-chat');
    if (emptyChat) {
        emptyChat.remove();
    }
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
    
    const date = new Date(message.created_at);
    const timeString = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
    
    messageDiv.innerHTML = `
        <img src="${message.sender.avatar_url}" alt="${message.sender.name}" class="message-avatar">
        <div class="message-content">
            <p class="message-text">${escapeHtml(message.message)}</p>
            <span class="message-time">${timeString}</span>
        </div>
    `;
    
    chatMessages.appendChild(messageDiv);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endsection

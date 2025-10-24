@extends('layouts.app')

@section('title', 'Messages')

@push('styles')
<style>
/* Ensure body and html have dark background */
html, body {
    background-color: #020d18 !important;
    min-height: 100%;
    margin: 0;
    padding: 0;
}

.ht-header {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

.chat-page-wrapper {
    background: #020d18 !important;
    min-height: 100vh;
    padding: 50px 0 100px;
}

.chat-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.chat-header {
    text-align: center;
    margin-bottom: 40px;
}

.chat-header h1 {
    color: #e9d736;
    font-size: 32px;
    margin-bottom: 10px;
}

.chat-requests-section {
    background: #0b1a2a;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    border: 1px solid #405266;
}

.chat-requests-section h2 {
    color: #e9d736;
    font-size: 20px;
    margin-bottom: 20px;
}

.chat-request-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px;
    background: #020d18;
    border-radius: 5px;
    margin-bottom: 10px;
    border: 1px solid #405266;
}

.request-user-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.request-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.request-details h4 {
    color: #fff;
    margin: 0 0 5px 0;
    font-size: 16px;
}

.request-details p {
    color: #abb7c4;
    margin: 0;
    font-size: 14px;
}

.request-actions {
    display: flex;
    gap: 10px;
}

.request-actions button {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.accept-btn {
    background: #4CAF50;
    color: white;
}

.accept-btn:hover {
    background: #45a049;
}

.delete-btn {
    background: #f44336;
    color: white;
}

.delete-btn:hover {
    background: #da190b;
}

.conversations-section {
    background: #0b1a2a;
    border-radius: 8px;
    margin-top:100px;
    padding: 20px;
    border: 1px solid #405266;
}

.conversations-section h2 {
    color: #e9d736;
    font-size: 20px;
    margin-bottom: 20px;
}

.conversation-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background: #020d18;
    border-radius: 5px;
    margin-bottom: 10px;
    border: 1px solid #405266;
    text-decoration: none;
    transition: all 0.3s ease;
}

.conversation-item:hover {
    background: #0d1b2a;
    border-color: #e9d736;
    transform: translateX(5px);
}

.conversation-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin-right: 15px;
}

.conversation-info {
    flex: 1;
}

.conversation-info h4 {
    color: #fff;
    margin: 0 0 5px 0;
    font-size: 16px;
}

.conversation-info p {
    color: #abb7c4;
    margin: 0;
    font-size: 14px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 400px;
}

.conversation-meta {
    text-align: right;
}

.conversation-time {
    color: #abb7c4;
    font-size: 12px;
    display: block;
    margin-bottom: 5px;
}

.unread-badge {
    background: #e9d736;
    color: #020d18;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #abb7c4;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 20px;
    color: #405266;
}
</style>
@endpush

@section('content')

<!-- BEGIN | Header -->
<header class="ht-header">
    <div class="container">
        @include('partials._header_top')
        @include('partials._search')
    </div>
</header>
<!-- END | Header -->

<div class="chat-page-wrapper">
    <div class="chat-container">

    @if($chatRequests->count() > 0)
        <div class="chat-requests-section">
            <h2>Chat Requests ({{ $chatRequests->count() }})</h2>
            @foreach($chatRequests as $request)
                <div class="chat-request-item" data-request-id="{{ $request->id }}">
                    <div class="request-user-info">
                        <img src="{{ $request->sender->avatar_url }}" alt="{{ $request->sender->name }}" class="request-avatar">
                        <div class="request-details">
                            <h4>{{ $request->sender->name }}</h4>
                            <p>{{ '@' . $request->sender->username }} wants to chat with you</p>
                            @if($request->message)
                                <p style="margin-top: 5px; font-style: italic;">"{{ $request->message }}"</p>
                            @endif
                        </div>
                    </div>
                    <div class="request-actions">
                        <button class="accept-btn" onclick="acceptRequest({{ $request->id }})">
                            Accept & Follow Back
                        </button>
                        <button class="delete-btn" onclick="deleteRequest({{ $request->id }})">
                            Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="conversations-section">
        <h2>Conversations</h2>
        @if($conversationUsers->count() > 0)
            @foreach($conversationUsers as $conversationUser)
                <a href="{{ route('chat.show', $conversationUser->id) }}" class="conversation-item">
                    <img src="{{ $conversationUser->avatar_url }}" alt="{{ $conversationUser->name }}" class="conversation-avatar">
                    <div class="conversation-info">
                        <h4>{{ $conversationUser->name }}</h4>
                        @if($conversationUser->last_message)
                            <p>
                                @if($conversationUser->last_message->sender_id == Auth::id())
                                    You: 
                                @endif
                                {{ Str::limit($conversationUser->last_message->message, 50) }}
                            </p>
                        @endif
                    </div>
                    <div class="conversation-meta">
                        @if($conversationUser->last_message)
                            <span class="conversation-time">
                                {{ $conversationUser->last_message->created_at->diffForHumans() }}
                            </span>
                        @endif
                        @if($conversationUser->unread_count > 0)
                            <span class="unread-badge">{{ $conversationUser->unread_count }}</span>
                        @endif
                    </div>
                </a>
            @endforeach
        @else
            <div class="empty-state">
                <i class="ion-ios-chatbubble-outline"></i>
                <p>No conversations yet. Start chatting with users who follow you back!</p>
            </div>
        @endif
    </div>
</div>
</div>

<script>
function acceptRequest(requestId) {
    if (!confirm('Accept this chat request and follow back?')) {
        return;
    }

    fetch(`/chat-request/${requestId}/accept`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                location.reload();
            }
        } else {
            alert(data.message || 'Failed to accept request.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to accept request. Please try again.');
    });
}

function deleteRequest(requestId) {
    if (!confirm('Delete this chat request?')) {
        return;
    }

    fetch(`/chat-request/${requestId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the request item from the page
            document.querySelector(`[data-request-id="${requestId}"]`).remove();
            
            // Check if there are no more requests
            const requestsSection = document.querySelector('.chat-requests-section');
            const remainingRequests = requestsSection.querySelectorAll('.chat-request-item');
            if (remainingRequests.length === 0) {
                requestsSection.remove();
            }
        } else {
            alert(data.message || 'Failed to delete request.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to delete request. Please try again.');
    });
}
</script>
@endsection

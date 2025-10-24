<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display list of conversations
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all users the authenticated user has conversations with
        $conversations = DB::table('chat_messages')
            ->select(DB::raw('
                CASE 
                    WHEN sender_id = ' . $user->id . ' THEN receiver_id 
                    ELSE sender_id 
                END as user_id,
                MAX(created_at) as last_message_time
            '))
            ->where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->groupBy('user_id')
            ->orderBy('last_message_time', 'desc')
            ->get();
        
        $conversationUsers = User::whereIn('id', $conversations->pluck('user_id'))
            ->get()
            ->map(function ($conversationUser) use ($user) {
                $conversationUser->unread_count = $user->getUnreadCountFrom($conversationUser);
                $conversationUser->last_message = ChatMessage::where(function($query) use ($user, $conversationUser) {
                    $query->where('sender_id', $user->id)->where('receiver_id', $conversationUser->id);
                })->orWhere(function($query) use ($user, $conversationUser) {
                    $query->where('sender_id', $conversationUser->id)->where('receiver_id', $user->id);
                })->latest()->first();
                return $conversationUser;
            });
        
        // Get pending chat requests
        $chatRequests = $user->receivedChatRequests()
            ->with('sender')
            ->latest()
            ->get();
        
        return view('chat.index', compact('conversationUsers', 'chatRequests'));
    }

    /**
     * Display conversation with a specific user
     */
    public function show(User $user)
    {
        $authUser = Auth::user();
        
        // Check if users can message each other
        if (!$authUser->canMessageUser($user)) {
            return redirect()->route('chat.index')
                ->with('error', 'You can only message users who follow you back.');
        }
        
        // Get conversation messages
        $messages = $authUser->getConversationWith($user);
        
        // Mark messages from the other user as read
        ChatMessage::where('sender_id', $user->id)
            ->where('receiver_id', $authUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return view('chat.show', compact('user', 'messages'));
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request, User $receiver)
    {
        $sender = Auth::user();
        
        // Check if users can message each other
        if (!$sender->canMessageUser($receiver)) {
            return response()->json([
                'success' => false,
                'message' => 'You can only message users who follow you back.'
            ], 403);
        }
        
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        // Prevent duplicate messages (check for same message sent within last 2 seconds)
        $recentDuplicate = ChatMessage::where('sender_id', $sender->id)
            ->where('receiver_id', $receiver->id)
            ->where('message', $request->message)
            ->where('created_at', '>', now()->subSeconds(2))
            ->exists();
            
        if ($recentDuplicate) {
            return response()->json([
                'success' => false,
                'message' => 'Please wait a moment before sending the same message again.'
            ], 429);
        }
        
        $message = ChatMessage::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => $request->message
        ]);
        
        return response()->json([
            'success' => true,
            'message' => $message->load('sender')
        ]);
    }

    /**
     * Get messages for a conversation
     */
    public function getMessages(User $user)
    {
        $authUser = Auth::user();
        
        if (!$authUser->canMessageUser($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $messages = $authUser->getConversationWith($user);
        
        // Mark messages as read
        ChatMessage::where('sender_id', $user->id)
            ->where('receiver_id', $authUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return response()->json([
            'success' => true,
            'messages' => $messages->load('sender')
        ]);
    }

    /**
     * Send a chat request
     */
    public function sendRequest(Request $request, User $receiver)
    {
        $sender = Auth::user();
        
        // Check if sender is following the receiver
        if (!$sender->isFollowing($receiver)) {
            return response()->json([
                'success' => false,
                'message' => 'You must follow this user to send a chat request.'
            ], 403);
        }
        
        // Check if they can already message (both follow each other)
        if ($sender->canMessageUser($receiver)) {
            return response()->json([
                'success' => false,
                'message' => 'You can already message this user.'
            ], 400);
        }
        
        // Check if request already exists
        if ($sender->hasSentChatRequestTo($receiver)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already sent a chat request to this user.'
            ], 400);
        }
        
        $request->validate([
            'message' => 'nullable|string|max:500'
        ]);
        
        $chatRequest = ChatRequest::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => $request->message
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Chat request sent successfully.',
            'chatRequest' => $chatRequest
        ]);
    }

    /**
     * Delete a chat request
     */
    public function deleteRequest(ChatRequest $chatRequest)
    {
        $user = Auth::user();
        
        // Only the receiver can delete the request
        if ($chatRequest->receiver_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $chatRequest->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Chat request deleted successfully.'
        ]);
    }

    /**
     * Accept a chat request (by following back)
     */
    public function acceptRequest(ChatRequest $chatRequest)
    {
        $user = Auth::user();
        
        // Only the receiver can accept the request
        if ($chatRequest->receiver_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $sender = $chatRequest->sender;
        
        // Follow the sender back if not already following
        if (!$user->isFollowing($sender)) {
            $user->following()->attach($sender->id);
        }
        
        // Delete the chat request
        $chatRequest->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Chat request accepted. You can now message each other.',
            'redirect' => route('chat.show', $sender->id)
        ]);
    }
}

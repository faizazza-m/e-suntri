<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function fetchMessages($user_id)
    {
        $messages = \App\Models\Message::where(function($q) use ($user_id) {
                $q->where('sender_id', Auth::id())
                  ->where('receiver_id', $user_id);
            })->orWhere(function($q) use ($user_id) {
                $q->where('sender_id', $user_id)
                  ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Mark as read
        \App\Models\Message::where('sender_id', $user_id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000'
        ]);

        $message = \App\Models\Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
            'is_read' => false,
        ]);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json($message);
    }
}

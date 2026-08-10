<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ChatController extends Controller
{
    public function index()
    {
        $myId = Auth::id();
        // Get users who have sent a message to Admin OR Admin has sent a message to
        // For simplicity, just load all Wali Santri (role_id = 3) for now, or users who have chats
        $contacts = User::where('role_id', 3)->get()->map(function($user) use ($myId) {
            $unreadCount = \App\Models\Message::where('sender_id', $user->id)
                ->where('receiver_id', $myId)
                ->where('is_read', false)
                ->count();

            return [
                'id' => $user->id,
                'name' => $user->name,
                'role' => 'Wali Santri',
                'color' => 'primary',
                'icon' => 'person',
                'unread_count' => $unreadCount
            ];
        });

        return view('admin.chat', compact('contacts'));
    }
}

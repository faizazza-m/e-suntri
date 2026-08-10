<?php

namespace App\Http\Controllers\Musyrif;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Halaqoh;

class ChatController extends Controller
{
    public function index()
    {
        // Get Wali Santri of the santri in this Musyrif's Halaqoh
        $halaqohs = Halaqoh::with('santri.wali')->where('musyrif_id', Auth::id())->get();
        $contacts = collect();
        $myId = Auth::id();

        foreach ($halaqohs as $halaqoh) {
            foreach ($halaqoh->santri as $santri) {
                // wali() is hasMany, so $santri->wali is a collection
                foreach ($santri->wali as $waliSantri) {
                    $waliUser = User::find($waliSantri->user_id);
                    if ($waliUser) {
                        $unreadCount = \App\Models\Message::where('sender_id', $waliUser->id)
                            ->where('receiver_id', $myId)
                            ->where('is_read', false)
                            ->count();

                        $contacts->push([
                            'id' => $waliUser->id,
                            'name' => 'Wali: ' . $santri->nama,
                            'role' => 'Wali Santri',
                            'color' => 'blue',
                            'icon' => 'family_restroom',
                            'unread_count' => $unreadCount
                        ]);
                    }
                }
            }
        }



        return view('musyrif.chat', ['contacts' => $contacts->unique('id')->values()]);
    }
}

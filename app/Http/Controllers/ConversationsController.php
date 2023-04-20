<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationsController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        // if user is logged in 
        $sender = Auth::user();

        if (!$sender) {
            return response()->json([
                'error' => 'we havent sent up email responses yet.'
            ], 403);
        }

        $conversation->messages()->create([
            'sender_id' => $sender->id,
            'sender_type' => get_class($sender),
            'body' => $request->body,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [],
        ], 201);
        // else  we've received an email 
    }
}

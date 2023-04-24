<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Mail\NewMessageAlert;
use App\Mail\NewMessagerAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ConversationsController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        // if user is logged in 
        if (!Auth::check()) {
            return response()->json([
                'error' => 'we havent sent up email responses yet.'
            ], 403);
        }

        $sender = Auth::user();

        $message = $conversation->messages()->create([
            'sender_id' => $sender->id,
            'sender_type' => get_class($sender),
            'body' => $request->body,
        ]);

        Mail::to($message->recipient())
            ->queue(new NewMessageAlert($message));

        return response()->json([
            'status' => 'success',
            'data' => [],
        ], 201);
        // else  we've received an email 
    }
}

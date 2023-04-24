<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Mail\NewMessageAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\NewMessageRequest;

class ConversationMessageController extends Controller
{
    public function store(NewMessageRequest $request, Conversation $conversation)
    {
        $message = $conversation->newMessage(Auth::user(), $request->body);

        Mail::to($message->recipient())->queue(new NewMessageAlert($message));

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => [],
        ], 201);
    }
}

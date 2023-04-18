<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtistConversationsController extends Controller
{
    public function index()
    {
        $artist = Auth::user();

        return response()->json([
            'conversations' => $artist->conversations
        ], 200);
    }

    public function show(Conversation $conversation)
    {
        return response()->json([
            'submission' => $conversation->submission,
            'messages' => [],
        ], 200);
    }
}

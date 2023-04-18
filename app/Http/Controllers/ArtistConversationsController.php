<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtistConversationsController extends Controller
{
    public function show(Conversation $conversation)
    {
        return response()->json([
            'messages' => collect([]),
        ], 200);
    }
}

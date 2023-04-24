<?php

namespace App\Http\Controllers;

use App\Models\Conversation;

class ArtistConversationsController extends Controller
{
    public function show(Conversation $conversation)
    {
        return response()->json([
            'messages' => $conversation->messages,
        ], 200);
    }

    public function destroy(Conversation $conversation)
    {
        $conversation->delete();

        return response()->json(null, 204);
    }
}

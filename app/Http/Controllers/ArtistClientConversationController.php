<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;

class ArtistClientConversationController extends Controller
{
    public function store(Request $request, User $user)
    {
        // validate reques
        $validated = $request->validate([
            'email' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $client = $user->clients()->create($validated);

        $data = array_merge();

        $submission = $user->submissions()->create([
            'idea' => $request->idea,
            'client_id' => $client->id,
        ]);

        $conversation = $user->conversations()->create([
            'client_id' => $client->id,
            'submission_id' => $submission->id,
        ]);

        dump($user->clients);

        return response($submission, 201);
    }
}

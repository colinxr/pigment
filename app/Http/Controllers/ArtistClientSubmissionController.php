<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ArtistClientSubmissionController extends Controller
{
    public function store(Request $request, User $user)
    {
        // validate reques
        $validated = $request->validate([
            'email' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        // return validation error

        // create the resources
        $client = $user->clients()->firstOrCreate($validated);

        $submission = $user->submissions()->create([
            'client_id' => $client->id,
            'idea' => $request->idea,
        ]);

        $conversation = $user->conversations()->create([
            'client_id' => $client->id,
            'submission_id' => $submission->id,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [],
            'message' => 'Your message has been successfully submitted.'
        ], 201);
    }
}

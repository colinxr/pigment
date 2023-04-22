<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtistSubmissionsController extends Controller
{
    public function index()
    {
        $artist = Auth::user();

        return response()->json([
            'submissions' => $artist->submissions
        ], 200);
    }

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

        $conversation = $submission->conversation()->create([
            'user_id' => $user->id,
            'client_id' => $client->id,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [],
            'message' => 'Your message has been successfully submitted.'
        ], 201);
    }
}
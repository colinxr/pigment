<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SubmissionRequest;

class ArtistSubmissionsController extends Controller
{
    public function index()
    {
        $artist = Auth::user();

        return response()->json([
            'submissions' => $artist->submissions
        ], 200);
    }

    public function store(SubmissionRequest $request, User $user)
    {
        $client = $user->clients()->firstOrCreate($request->all());

        $submission = $user->submissions()->create([
            'client_id' => $client->id,
            'idea' => $request->idea,
        ]);

        if ($request->attachments) {
            foreach ($request->attachments as $attachment) {
                $image = $submission->addMedia($attachment)->toMediaCollection('attachments');
            }
        }


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

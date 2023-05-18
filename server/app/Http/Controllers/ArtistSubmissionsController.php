<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SubmissionRequest;

class ArtistSubmissionsController extends Controller
{
    public function index()
    {
        $per_page = request()->query('per_page', 50);

        $subs = Auth::user()->submissions()
            ->with(['messages', 'lastMessage', 'client'])
            ->paginate($per_page);

        return response()->json(['submissions' => $subs], 200);
    }

    public function store(SubmissionRequest $request, User $user)
    {
        $client = $user->clients()->firstOrCreate(['email' => $request->email], $request->except('email'));

        $submission = $user->submissions()->create([
            'client_id' => $client->id,
            'idea' => $request->idea,
        ]);

        if ($request->attachments) {
            foreach ($request->attachments as $attachment) {
                $image = $submission->addMedia($attachment)->toMediaCollection('attachments');
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Your message has been successfully submitted.',
            'data' => [],
        ], 201);
    }

    public function show(Submission $submission)
    {
        return response()->json([
            'messages' => $submission->messages,
        ], 200);
    }

    public function destroy(Submission $submission)
    {
        $submission->delete();

        return response()->json(null, 204);
    }
}

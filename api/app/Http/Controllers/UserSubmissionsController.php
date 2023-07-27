<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SubmissionRequest;

class UserSubmissionsController extends Controller
{
    public function index()
    {
        $per_page = request()->query('per_page', 50);

        $subs = Auth::user()->submissions()
            ->with(['messages', 'lastMessage', 'client'])
            ->orderByRaw('COALESCE((SELECT created_at FROM messages WHERE submission_id = submissions.id ORDER BY created_at DESC LIMIT 1), submissions.created_at) DESC')
            ->paginate($per_page);

        return response()->json(['submissions' => $subs], 200);
    }

    public function store(SubmissionRequest $request, String $username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.',
                'data' => [],
            ], 401);
        }

        $client = $user->clients()->firstOrCreate(['email' => $request->email], $request->except('idea'));

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
        $submission->update(['has_new_messages' => false]);

        return response()->json([
            'messages' => $submission->messages,
        ], 200);
    }

    public function destroy(Submission $submission)
    {
        $submission->delete();

        return response()->json(null, 204);
    }

    public function update(Submission $submission)
    {
        $submission->update(['has_new_messages' => false]);

        return response()->json(null, 204);
    }
}

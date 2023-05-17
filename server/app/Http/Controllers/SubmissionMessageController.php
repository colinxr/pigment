<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Conversation;
use App\Mail\NewMessageAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\NewMessageRequest;

class SubmissionMessageController extends Controller
{
    public function store(NewMessageRequest $request, Submission $submission)
    {
        $message = $submission->newMessage(Auth::user(), $request->body);

        if ($request->attachments) {
            foreach ($request->attachments as $attachment) {
                $image = $message->addMedia($attachment)->toMediaCollection('attachments');
            }
        }

        Mail::to($message->recipient())->queue(new NewMessageAlert($message));

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => [],
        ], 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Conversation;
use App\Mail\NewMessageAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\NewMessageRequest;

class SubmissionMessageController extends Controller
{
    public function store(NewMessageRequest $request, Submission $submission)
    {
        try {
            $message = $submission->newMessage(Auth::user(), $request->body);

            $attachments = collect($request->attachments);

            $attachments->each(function ($fileName) use ($message) {
                if (!Storage::disk('public')->exists("temp/{$fileName}")) return null;

                // Attach the file to the media collection of the message
                $message->addMedia(storage_path("app/public/temp/{$fileName}"))
                    ->toMediaCollection('attachments');
            });

            Mail::to($message->recipient())->queue(new NewMessageAlert($message));

            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully',
                'data' => $message,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'data' => $th,
            ], 500);
        }
    }
}

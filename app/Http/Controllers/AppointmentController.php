<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NewAppointmentRequest;
use App\Interfaces\GoogleCalendarInterface;
use App\Services\GoogleApiService;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{

    private $gCalService;

    public function __construct(GoogleCalendarInterface $gCalService)
    {
        $this->gCalService = $gCalService;
    }

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => Auth::user()->appointments,
        ], 200);
    }


    public function store(NewAppointmentRequest $request, Submission $submission)
    {
        if ($submission->user->id !== Auth::user()->id) {
            return response()->json([
                'error' => "You're not authorized to do that."
            ], 403);
        }

        $client = $this->gCalService->getClient();

        if (!auth()->user()->access_token) {
            return response()->json([
                'status' => '403',
                'message' => 'User is unauthorized.',
                'data' => $client->createAuthUrl(),
            ], 403);
        }

        if (auth()->user()->isTokenExpired()) {
            $refresh_token = auth()->user()->getAccessToken()->refresh_token;
            $token = $client->fetchAccessTokenWithRefreshToken($refresh_token);
            auth()->user()->update(['access_token' => $token]);
        }

        $client->setAccessToken(auth()->user()->access_token);

        $appointment = $submission->appointment()->create(
            array_merge(
                $request->toArray(),
                [
                    'user_id' => $submission->user_id,
                    'startDateTime' => $request->start,
                    'endDateTime' => $request->end,
                ]
            )
        );

        $event = $this->gCalService->saveEvent($appointment);

        $appointment->update(['event_id' => $event->getId()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment saved successfully',
            'data' => [
                'appointment' => $appointment,
                'event' => $event,
            ]
        ], 201);
    }
}

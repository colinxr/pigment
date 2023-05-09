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
        $client = $this->gCalService->getClient();
        $client->setAccessToken(auth()->user()->access_token);

        $appointments = Auth::user()->appointments;
        $events = $this->gCalService->listEvents();

        return response()->json([
            'status' => 'success',
            'data' => $events,
        ], 200);
    }


    public function store(NewAppointmentRequest $request, Submission $submission)
    {
        $client = $this->gCalService->getClient();

        if ($submission->user->id !== Auth::user()->id) {
            return response()->json([
                'error' => "You're not authorized to do that."
            ], 403);
        }

        $client = $this->gCalService->getClient();
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

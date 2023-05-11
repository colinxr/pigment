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
        $this->gCalService->setToken(request()->user()->access_token);

        $appointments = Auth::user()->appointments;
        $events = $this->gCalService->listEvents();

        return response()->json([
            'status' => 'success',
            'data' => $events,
        ], 200);
    }


    public function store(NewAppointmentRequest $request, Submission $submission)
    {
        if ($submission->user->id !== Auth::user()->id) {
            return response()->json([
                'error' => "You're not authorized to do that."
            ], 403);
        }

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

        $this->gCalService->setToken(auth()->user()->access_token);
        
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

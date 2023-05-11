<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Services\GoogleApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\GoogleCalendarService;
use App\Interfaces\GoogleCalendarInterface;
use App\Http\Requests\NewAppointmentRequest;

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
                    // 'startDateTime' => $request->startDateTime,
                    // 'endDateTime' => $request->endDateTime,
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

    public function update(NewAppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->toArray());

        $this->gCalService->setToken(auth()->user()->access_token);
        $this->gCalService->updateEvent($appointment->event_id, $appointment);

        return response()->json([
            'message' => 'Resource updated successfully',
            'data' => $appointment,
        ], 204);
    }
}

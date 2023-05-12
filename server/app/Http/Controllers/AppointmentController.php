<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
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
            'data' => $appointments,
        ], 200);
    }


    public function store(NewAppointmentRequest $request, Submission $submission)
    {
        if ($submission->user->id !== Auth::user()->id) {
            return response()->json([
                'error' => "You're not authorized to do that."
            ], 403);
        }

        $appt = $submission->appointment()->create(
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
        
        $event = $this->gCalService->saveEvent($appt);

        $appt->update(['event_id' => $event->getId()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment saved successfully',
            'data' => [
                'appointment' => $appt,
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

    public function destroy(Appointment $appointment)
    {
        $this->gCalService->setToken(auth()->user()->access_token);
        $this->gCalService->deleteEvent($appointment->event_id);

        $appointment->delete();

        return response()->json([], 204);
    }
}

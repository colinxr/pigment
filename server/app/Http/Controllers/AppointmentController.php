<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        if (!request()->query('client')) {
            return response()->json(['data' => Auth::user()->appointments], 200);
        }

        return response()->json([
            'data' => Auth::user()->appointmentsForClient(request()->query('client_id'))
        ], 200);
    }

    public function show(Appointment $appointment)
    {
        Gate::allows('view', Auth::user(), $appointment);

        return response()->json(['data' => $appointment], 200);
    }

    public function submissionIndex(Submission $submission)
    {
        Gate::allows('viewSubmission', Auth::user(), $submission);

        return response()->json([
            'data' => $submission->sortedAppointments(),
        ], 200);
    }

    public function store(NewAppointmentRequest $request, Submission $submission)
    {
        Gate::allows('viewSubmission', Auth::user(), $submission);

        $appt = $submission->appointments()->create(
            array_merge(
                $request->toArray(),
                ['user_id' => $submission->user_id,]
            )
        );

        $this->gCalService->setToken(Auth::user()->access_token);

        $event = $this->gCalService->saveEvent($appt);

        $appt->update(['event_id' => $event->getId()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment saved successfully',
            'data' => [
                'appointment' => $appt,
                'event' => [],
            ]
        ], 201);
    }

    public function update(NewAppointmentRequest $request, Appointment $appointment)
    {
        Gate::allows('view', Auth::user(), $appointment);

        $appointment->update($request->toArray());

        $this->gCalService->setToken(Auth::user()->access_token);
        $this->gCalService->updateEvent($appointment->event_id, $appointment);

        return response()->json([
            'message' => 'Resource updated successfully',
            'data' => $appointment,
        ], 204);
    }

    public function destroy(Appointment $appointment)
    {
        Gate::allows('view', Auth::user(), $appointment);

        $this->gCalService->setToken(Auth::user()->access_token);
        $this->gCalService->deleteEvent($appointment->event_id);

        $appointment->delete();

        return response()->json([], 204);
    }
}

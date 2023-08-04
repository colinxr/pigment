<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Submission;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Events\AppointmentCreated;
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

    public function index(Request $request)
    {
        if ($request->query('client_id')) {
            $client = Client::findOrFail($request->query('client_id'));

            Gate::allows('viewSubmission', Auth::user(), $client);

            return response()->json([
                'data' => Auth::user()->appointmentsForClient($request->query('client_id'))
            ], 200);
        }

        if ($request->query('submission_id')) {
            $submission = Submission::findOrFail($request->query('submission_id'));

            Gate::allows('viewSubmission', Auth::user(), $submission);

            return response()->json([
                'data' => $submission->sortedAppointments(),
            ], 200);
        }

        return response()->json(['data' => Auth::user()->lastestAppointments], 200);
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

    public function store(NewAppointmentRequest $request)
    {
        $data =  array_merge(
            $request->toArray(),
            ['user_id' => Auth::user()->id,]
        );

        if (!$request->query('submission_id')) {
            $client = Auth::user()->clients()->firstOrCreate(['email' => $request->client]);

            $submission = Submission::create([
                'client_id' => $client->id,
                'user_id' => Auth::user()->id,
                'idea' => $request->description,
            ]);

            $data['submission_id'] = $submission->id;
        } else {
            $data['submission_id'] = $request->query('submission_id');
        }

        $appt = Appointment::create($data);

        AppointmentCreated::dispatch(Auth::user(), $appt);

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment saved successfully',
            'data' => [
                'appointment' => $appt,
            ]
        ], 201);
    }

    public function update(NewAppointmentRequest $request, Appointment $appointment)
    {
        Gate::allows('view', Auth::user(), $appointment);

        $appointment->update($request->toArray());

        $this->gCalService->setToken(Auth::user()->access_token);
        $this->gCalService->updateEvent($appointment);

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

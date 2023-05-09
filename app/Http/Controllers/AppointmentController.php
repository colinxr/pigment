<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NewAppointmentRequest;
use App\Interfaces\GoogleCalendarInterface;
use App\Services\GoogleApiService;
use App\Services\GoogleCalendarService;

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
        // dd(auth()->user()->access_token);

        $client = $this->gCalService->getClient();
        $client->setAccessToken(auth()->user()->access_token);

        if ($submission->user->id !== Auth::user()->id) {
            return response()->json([
                'error' => "You're not authorized to do that."
            ], 403);
        }

        $data = array_merge(
            $request->toArray(),
            [
                'user_id' => $submission->user_id,
                'startDateTime' => $request->start,
                'endDateTime' => $request->end,
            ],
        );

        $appointment = $submission->appointment()->create($data);

        $event = $this->gCalService->saveEvent($appointment);

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment saved successfully',
            'data' => $appointment
        ], 201);
    }
}

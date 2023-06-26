<?php

namespace App\Http\Controllers;

use App\Services\CalendarAppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarScheduleController extends Controller
{

    function index(Request $request)
    {
        if (!$request->query('duration')) {
            return response()->json([
                'error' => 'Missing Duration query parameter.',
                'status' => 'error'
            ], 501);
        }

        $calService = new CalendarAppointmentService(Auth::user());

        return response()->json([
            'data' => $calService->getNextAvailableSlots($request->query('duration')),
        ], 200);
    }


    function store(Request $request)
    {
        Auth::user()->calendar->update([
            'schedule' => $request->schedule,
        ]);

        return response()->json([
            'message' => 'Resource updated successfully',
            'data' => Auth::user()->calendar->schedule,
        ], 201);
    }

    function update(Request $request)
    {
    }

    public function show()
    {
        return response()->json([
            'message' => 'Resource updated successfully',
            'data' => Auth::user()->calendar->schedule,
        ], 201);
    }
}

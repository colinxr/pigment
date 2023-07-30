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
            ], 422);
        }

        try {
            $calService = new CalendarAppointmentService(Auth::user());
            $slots = $calService->getNextAvailableSlots($request->query('duration'));
            return response()->json(['data' => $slots,], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage,]);
        }
    }


    function store(Request $request)
    {
        Auth::user()->calendar->update([
            'schedule' => $request->schedule,
        ]);

        return response()->json([
            'message' => 'Schedule updated',
            'data' => Auth::user()->calendar->schedule,
        ], 201);
    }

    function update(Request $request)
    {
    }

    public function show()
    {
        return response()->json([
            'data' => Auth::user()->calendar->schedule,
        ], 201);
    }
}

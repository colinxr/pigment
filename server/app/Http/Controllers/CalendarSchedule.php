<?php

namespace App\Http\Controllers;

use Auth as GlobalAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarSchedule extends Controller
{

    function index(Request $request)
    {
        if (!$request->query('duration')) {
            return response()->json([
                'error' => 'Missing Duration query parameter.',
                'status' => 'error'
            ], 501);
        }

        return response()->json([
            'data' => Auth::user()->getNextAvailableSlots($request->query('duration')),
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
}

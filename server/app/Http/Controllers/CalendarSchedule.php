<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarSchedule extends Controller
{
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

<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function store(Request $request, Submission $submission)
    {
        if (Auth::user()->id !== $submission->user->id) {
            return response()->json([
                'error' => "You're not authorized to do that."
            ], 403);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'price' => 'required',
            'deposit' => 'sometimes',
        ]);

        $data = array_merge(
            ['user_id' => $submission->user_id],
            $request->toArray(),
        );

        $appointment = $submission->appointment()->create($data);

        return response($appointment, 201);
    }
}

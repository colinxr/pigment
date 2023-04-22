<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(Request $request, Submission $submission)
    {
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

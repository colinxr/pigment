<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventWebhookController extends Controller
{
    public function update(Request $request)
    {
        $appointment = Appointment::firstOrFail(['event_id' => $request->query('event_id')],);

        Log::info(json_encode($request->toArray()));
    }
}

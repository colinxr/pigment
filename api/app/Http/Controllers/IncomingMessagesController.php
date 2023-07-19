<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IncomingMessagesController extends Controller
{
    public function store(Request $request)
    {
        Log::info(json_encode($request->json()->all()));
        // Do whatever you need with the email data, such as saving it to the database or sending a response.

        // Log::info(json_encode($request->payload));
        return response()->json(['message' => 'Email processed successfully']);
    }
}

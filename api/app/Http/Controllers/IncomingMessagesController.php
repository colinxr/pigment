<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IncomingMessagesController extends Controller
{
    public function store(Request $request)
    {

        Log::info(json_encode($request->payload));
    }
}

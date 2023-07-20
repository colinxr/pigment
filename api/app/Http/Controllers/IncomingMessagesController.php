<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\IncomingMessageService;

class IncomingMessagesController extends Controller
{
    private $messageService;

    public function __construct(IncomingMessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function store(Request $request)
    {
        Log::info(json_encode($request->all()));

        try {
            $message = $this->messageService->handleInboundMessage($request->all());

            return response()->json([], 204);
        } catch (\Throwable $th) {
            \Log::info($th);
            return response()->json([], 500);
        }
    }
}

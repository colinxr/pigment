<?php

namespace App\Http\Controllers;

use App\Services\IncomingMessageService;
use Illuminate\Http\Request;

class IncomingMessagesController extends Controller
{
    private $messageService;

    public function __construct(IncomingMessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function store(Request $request)
    {
        try {
            $message = $this->messageService->handleInboundMessage($request->all());

            return response()->json([], 204);
        } catch (\Throwable $th) {
            return response()->json([], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserClientController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Auth::user()->clients,
        ], 200);
    }

    public function show(Client $client)
    {
        if ($client->user_id !== Auth::user()->id) {
            return response()->json([
                'error' => 'You don\'t have permission to view this client.'
            ], 400);
        }

        return response()->json([
            'data' => $client,
        ], 200);
    }

    public function update(Client $client)
    {
        try {
            if ($client->user_id !== Auth::user()->id) {
                return response()->json([
                    'error' => 'You don\'t have permission to view this client.'
                ], 400);
            }

            $client->update(request()->only(['email', 'first_name', 'last_name']));

            return response()->json([
                'status' => 'success',
                'data' => $client,
                'messages' => 'Client updated successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'error' => $th->getMessage(),
            ]);
        }
    }
}

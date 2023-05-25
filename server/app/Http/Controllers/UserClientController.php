<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserClientController extends Controller
{
    public function update(Request $request, string $email)
    {
        try {
            $client = Client::where('email', $email)->where('user_id', Auth::user()->id)->first();

            $client->update($request->only(['email', 'first_name', 'last_name']));

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

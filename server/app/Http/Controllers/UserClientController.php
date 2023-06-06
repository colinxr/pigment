<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        Gate::authorize('clientBelongsToUser', $client);


        return response()->json([
            'data' => $client,
        ], 200);
    }

    public function update(Client $client)
    {
        Gate::authorize('clientBelongsToUser', $client);

        try {
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

    public function destroy(Client $client)
    {
        Gate::authorize('clientBelongsToUser', $client);

        try {
            $client->delete();

            return response()->json([
                'status' => 'success',
                'data' => $client,
                'messages' => 'Client successfully deleted.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'error' => $th->getMessage(),
            ]);
        }
    }
}

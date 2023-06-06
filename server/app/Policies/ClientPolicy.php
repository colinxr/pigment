<?php

namespace App\Policies;

use App\Models\Client;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    public function clientBelongsToUser(Client $client)
    {
        return $client->user_id === Auth::user()->id
            ? Response::allow()
            : response()->json([
                'error' => 'You don\'t have permission to view this client.'
            ], 400);
    }
}

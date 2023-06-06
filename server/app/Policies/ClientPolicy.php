<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Client $client)
    {
        Log::info('client user id');
        Log::info($client->user_id);
        Log::info('user id');
        Log::info($user->id);

        return $user->id === $client->user_id
            ? Response::allow()
            : response()->json([
                'error' => 'You don\'t have permission to view this client.'
            ], 400);
    }
}

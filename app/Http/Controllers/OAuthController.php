<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\GoogleApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\GoogleCalendarService;

class OAuthController extends Controller
{
    protected $google;

    public function __construct(GoogleApiService $google)
    {
        $this->google = $google;
    }

    public function index()
    {
        if (!request()->code) {
            $auth_url = $this->google->client()->createAuthUrl();

            return redirect()->away($auth_url);
        }

        dd(request()->token());

        $response = Http::withToken(auth()->user()->token)
            ->post('/api/oauth/google/callback', ['code' => request()->code]);

        return redirect('/');
    }

    public function update()
    {
        if (!request()->code) {
            return response()->json([
                'error' => 'Bad request: no authentication code provided',
                'status' => 'error',
            ], 401);
        }

        $token = $this->google->client()
            ->fetchAccessTokenWithAuthCode(request()->code);

        auth()->user()->storeAccessToken($token);

        $gCalService = new GoogleCalendarService($this->google);

        $calendar = $gCalService->checkCalendarExists(auth()->user()->calendar_id);

        if ($calendar->getId() !== auth()->user()->calendar_id) {
            auth()->user()->update(['calendar_id' => $calendar->getId()]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully authenticated Google Calendar',
            'data' => [],
        ], 200);
    }
}

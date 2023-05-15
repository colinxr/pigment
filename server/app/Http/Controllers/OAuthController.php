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
    protected $googleApi;

    public function __construct(GoogleApiService $googleApi)
    {
        $this->googleApi = $googleApi;
    }

    public function index()
    {
        if (!request()->code) {
            $auth_url = $this->googleApi->client()->createAuthUrl();

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

        $token = $this->googleApi->client()
            ->fetchAccessTokenWithAuthCode(request()->code);

        auth()->user()->storeAccessToken($token);

        $gCalService = new GoogleCalendarService($this->googleApi);

        $gCalService->setToken($token);

        $calendar = $gCalService->checkCalendarExists(auth()->user()->calendar_id);

        if ($calendar->id !== auth()->user()->calendar_id) {
            auth()->user()->update(['calendar_id' => $calendar->id]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully authenticated Google Calendar',
            'data' => $calendar,
        ], 200);
    }
}
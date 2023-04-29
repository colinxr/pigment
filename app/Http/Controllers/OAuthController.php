<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleApiService;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
    protected $apiClient;

    public function __construct(GoogleApiService $apiClient)
    {
        $this->apiClient = $apiClient->client();
    }

    public function index()
    {
        dd(session()->token());

        if (!request()->code) {

            $auth_url = $this->apiClient->createAuthUrl();

            return redirect()->away($auth_url);
        }

        $response = Http::withToken(auth()->user()->token)
            ->post('/api/oauth/google/callback', ['code' => request()->code]);

        return redirect('/');
    }

    public function update()
    {
        if (!request()->code) {
            return response()->json([
                'error' => 'Bad request: now authentication code provided',
                'status' => 'error',
            ], 401);
        }

        $token = $this->apiClient->fetchAccessTokenWithAuthCode(request()->code);

        $this->apiClient->setAccessToken($token);

        auth()->user()->google_access_token = $token;

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully authenticated Google Calendar',
            'data' => [],
        ], 200);
    }
}

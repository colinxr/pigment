<?php

namespace App\Http\Controllers;

use App\Services\GoogleApiService;
use Illuminate\Http\Request;

class OAuthController extends Controller
{
    protected $apiClient;

    public function __construct(GoogleApiService $apiClient)
    {
        $this->apiClient = $apiClient->client();
    }

    public function store()
    {
        if (request()->code) {
            $token = $this->apiClient->fetchAccessTokenWithAuthCode(request()->code);
            $this->apiClient->setAccessToken($token);

            auth()->user()->google_access_token = $token;

            dd(auth()->user());
        }

        return redirect('/');;
    }
}

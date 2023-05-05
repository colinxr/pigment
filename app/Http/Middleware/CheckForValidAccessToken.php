<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\GoogleApiService;
use Symfony\Component\HttpFoundation\Response;

class CheckForValidAccessToken
{

    protected $apiClient;

    public function __construct(GoogleApiService $apiClient)
    {
        $this->apiClient = $apiClient->client();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment() === 'testing') {
            return $next($request);
        }

        // Set the user's access token and refresh token on the Google client
        // $this->apiClient->setAccessToken(auth()->user()->getAccessToken());

        // Check if access token has expired
        if ($this->apiClient->isAccessTokenExpired()) {
            // Refresh access token
            $this->apiClient->fetchAccessTokenWithRefreshToken($this->apiClient->getRefreshToken());
            // Update user's access token in the database or session
            auth()->user->storeAccessToken($this->apiClient->getAccessToken());
        }

        return $next($request);
    }
}

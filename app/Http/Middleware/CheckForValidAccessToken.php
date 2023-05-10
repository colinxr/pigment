<?php

namespace App\Http\Middleware;

use App\Interfaces\GoogleCalendarInterface;
use Closure;
use Illuminate\Http\Request;
use App\Services\GoogleApiService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckForValidAccessToken
{

    public $user;
    protected $apiClient;

    public function __construct(GoogleCalendarInterface $gCalService)
    {
        $this->apiClient = $gCalService->getClient();
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

        if (!$request->user()->access_token) {
            $auth_url = $this->apiClient->createAuthUrl();

            return response()->json([
                'status' => '403',
                'message' => 'user is unauthenticated',
                'data' => $auth_url,
            ], 403);
        }

        if ($request->user()->isTokenExpired()) {
            $refresh_token = $request->user()->getAccessToken()->refresh_token;

            $token = $this->apiClient
                ->fetchAccessTokenWithRefreshToken($refresh_token);

            $request->user()->update(['access_token' => $token]);
        }

        return $next($request);
    }
}

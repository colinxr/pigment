<?php

use App\Models\User;
use App\Services\GoogleApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/auth-token', function () {
    $user = User::where('email', 'colinxr@gmail.com')->first();;

    return response()->json([
        'token' => $user->tokens->first()->token
    ]);
});

Route::get('/csrf-token', function () {
    return response()->json([
        'csrf' => csrf_token(),
    ]);
});

Route::get('/oauth/google/callback', function (GoogleApiService $apiClient) {
    if (!request()->code) {

        $auth_url = $apiClient->client()->createAuthUrl();

        return redirect()->away($auth_url);
    }

    $user = User::where('email', 'colinxr@gmail.com')->first();

    $response = Http::acceptJson()->withToken($user->tokens->first()->token)
        ->post(config('app.url') . '/api/oauth/google/callback', ['code' => request()->code]);

    if ($response->successful()) {
        // Process successful response
        return $response->json();
    } else {
        // Handle error response

        return $response->json();
    }
});

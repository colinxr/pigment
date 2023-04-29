<?php

use Illuminate\Http\Request;
use App\Services\GoogleApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/oauth/google/callback', function () {
    if (!request()->code) {

        $auth_url = $this->apiClient->createAuthUrl();

        return redirect()->away($auth_url);
    }

    $response = Http::withToken(session()->token())
        ->post('/api/oauth/google/callback', ['code' => request()->code]);

    return redirect('/');
});

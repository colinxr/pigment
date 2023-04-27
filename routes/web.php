<?php

use Illuminate\Http\Request;
use App\Services\GoogleApiClient;
use App\Services\GoogleApiService;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/oauth/google/callback', function (GoogleApiService $service) {

    if (request()->code) {
        $token = $service->client()->fetchAccessTokenWithAuthCode(request()->code);
        $service->client()->setAccessToken($token);

        auth()->user()->google_access_token = $token;

        dd(auth()->user());
    }

    return redirect('/');;
});

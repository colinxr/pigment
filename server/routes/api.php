<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Services\GoogleCalendarService;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OAuthController;
use App\Interfaces\GoogleCalendarInterface;
use App\Http\Controllers\UserClientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\ArtistSubmissionsController;
use App\Http\Controllers\SubmissionMessageController;
use App\Http\Controllers\ArtistConversationsController;
use App\Http\Controllers\ConversationMessageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (GoogleCalendarInterface $gCalService) {
    return request()->user();
});

Route::post('/users', [UserController::class, 'store']);

Route::post('/users/{user}/submissions', [ArtistSubmissionsController::class, 'store']);

Route::post('/sanctum/token', 'AuthController@store');

Route::post('/register', 'AuthController@register');

Route::post('/login', 'AuthController@login');

Route::middleware('auth:sanctum')->group(function () {
    // Auth Routes
    Route::get('/oauth/google/callback', [OAuthController::class, 'index']);
    Route::post('/oauth/google/callback', [OAuthController::class, 'update']);

    Route::get('/logout', 'AuthController@logout');

    // Submissions and Nested Resource
    Route::get('/submissions', [ArtistSubmissionsController::class, 'index']);
    Route::get('/submissions/{submission}', [ArtistSubmissionsController::class, 'show']);
    Route::delete('/submissions/{submission}', [ArtistSubmissionsController::class, 'destroy']);
    Route::post('/submissions/{submission}/message', [SubmissionMessageController::class, 'store']);
    Route::get('/submissions/{submission}/appointments', [AppointmentController::class, 'show']);

    Route::put('/clients/{email}', [UserClientController::class, 'update']);

    Route::get('/appointments', [AppointmentController::class, 'index']);

    Route::post('/users/{user}', [UserController::class, 'update']);

    Route::middleware('hasValidAccessToken')->group(function () {
        Route::get('/user', function (GoogleCalendarInterface $gCalService) {
            if (!request()->user()->calendar_id) {
                $gCalService->setToken(request()->user()->access_token);
                $gCalService->getCalendarId();
            }


            return request()->user();
        });


        // Appointment Routes
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update']);
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy']);
        Route::post('/submissions/{submission}/appointments', [AppointmentController::class, 'store']);
    });
});

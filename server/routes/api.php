<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Services\GoogleCalendarService;
use App\Http\Controllers\AuthController;
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

// Route::post('/users', [UserController::class, 'store']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/sanctum/token', [AuthController::class, 'store']);

Route::post('/users/{user}/submissions', [ArtistSubmissionsController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth Routes
    Route::get('/oauth/google/callback', [OAuthController::class, 'index']);
    Route::post('/oauth/google/callback', [OAuthController::class, 'update']);

    Route::get('/logout', [AuthController::class, 'logout']);

    // Submissions and Nested Resource
    Route::get('/submissions', [ArtistSubmissionsController::class, 'index']);
    Route::get('/submissions/{submission}', [ArtistSubmissionsController::class, 'show']);
    Route::delete('/submissions/{submission}', [ArtistSubmissionsController::class, 'destroy']);
    Route::post('/submissions/{submission}/message', [SubmissionMessageController::class, 'store']);
    // refactor this action 
    Route::get('/submissions/{submission}/appointments', [AppointmentController::class, 'submissionIndex']);


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
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show']);
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update']);
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy']);
        Route::post('/submissions/{submission}/appointments', [AppointmentController::class, 'store']);
    });

    // Client Routes
    Route::get('/clients', [UserClientController::class, 'index']);
    Route::get('/clients/{client}', [UserClientController::class, 'show']);
    Route::put('/clients/{client}', [UserClientController::class, 'update']);
    Route::delete('/clients/{client}', [UserClientController::class, 'destroy']);
});

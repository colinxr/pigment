<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OAuthController;
use App\Interfaces\GoogleCalendarInterface;
use App\Http\Controllers\UserClientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\EventWebhookController;
use App\Http\Controllers\CalendarScheduleController;
use App\Http\Controllers\UserSubmissionsController;
use App\Http\Controllers\SubmissionMessageController;

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

Route::post('/register', [AuthController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/sanctum/token', [AuthController::class, 'storeToken']);

Route::post('/users/{user}/submissions', [UserSubmissionsController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth Routes
    Route::get('/oauth/google/callback', [OAuthController::class, 'index']);
    Route::post('/oauth/google/callback', [OAuthController::class, 'update']);

    Route::get('/logout', [AuthController::class, 'logout']);

    // Submissions and Nested Resource
    Route::get('/submissions', [UserSubmissionsController::class, 'index']);
    Route::get('/submissions/{submission}', [UserSubmissionsController::class, 'show']);
    Route::delete('/submissions/{submission}', [UserSubmissionsController::class, 'destroy']);
    Route::get('/submissions/{submission}/read', [UserSubmissionsController::class, 'update']);
    Route::post('/submissions/{submission}/message', [SubmissionMessageController::class, 'store']);
    // refactor this action 
    Route::get('/submissions/{submission}/appointments', [AppointmentController::class, 'submissionIndex']);


    Route::get('/appointments', [AppointmentController::class, 'index']);

    Route::post('/users/{user}', [UserController::class, 'update']);

    Route::middleware('hasValidAccessToken')->group(function () {
        // Appointment Routes
        Route::post('/appointments', [AppointmentController::class, 'store']);
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show']);
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update']);
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy']);
    });

    // Client Routes
    Route::get('/clients', [UserClientController::class, 'index']);
    Route::get('/clients/{client}', [UserClientController::class, 'show']);
    Route::put('/clients/{client}', [UserClientController::class, 'update']);
    Route::delete('/clients/{client}', [UserClientController::class, 'destroy']);

    Route::get('/calendars/slots', [CalendarScheduleController::class, 'index']);
    Route::get('/calendars/schedule', [CalendarScheduleController::class, 'show']);
    Route::post('/calendars/schedule', [CalendarScheduleController::class, 'store']);

    Route::post('/webhook/events', [EventWebhookController::class, 'update']);
});

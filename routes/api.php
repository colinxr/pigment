<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\ArtistSubmissionsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users', [UserController::class, 'store']);

Route::post('/users/{user}/submissions', [ArtistSubmissionsController::class, 'store']);

Route::get('/submissions', [ArtistSubmissionsController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/oauth/google/callback', [OAuthController::class, 'update']);

    Route::middleware('hasValidAccessToken')->group(function () {
        Route::post('/users/{user}', [UserController::class, 'update']);

        Route::get('/appointments', [AppointmentController::class, 'index']);

        Route::post('/submissions/{submission}/appointments', [AppointmentController::class, 'store']);

        Route::get('/conversations/{conversation}', [ArtistConversationsController::class, 'show']);

        Route::delete('/conversations/{conversation}', [ArtistConversationsController::class, 'destroy']);

        Route::post('/conversations/{conversation}/message', [ConversationMessageController::class, 'store']);
    });
});

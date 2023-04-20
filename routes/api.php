<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\ArtistSubmissionsController;
use App\Http\Controllers\ArtistConversationsController;

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

Route::post('/artist/{user}/submissions', [ArtistSubmissionsController::class, 'store']);

Route::get('/submissions', [ArtistSubmissionsController::class, 'index']);

Route::get('/conversations/{conversation}', [ArtistConversationsController::class, 'show']);
Route::post('/conversations/{conversation}/message', [ConversationsController::class, 'store']);

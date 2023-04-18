<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtistMessagesController extends Controller
{
    public function index()
    {
        $artist = Auth::user();

        return response()->json([
            'conversations' => $artist->conversations
        ], 200);
    }
}

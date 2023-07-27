<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserExistsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $username)
    {
        $user = User::where('username', $username)->pluck('first_name', 'last_name', 'username')->first();

        if (!$user) {
            return  response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'user' => $user
        ], 200);
    }
}

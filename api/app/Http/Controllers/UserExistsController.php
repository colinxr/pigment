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
        $user = User::where('username', $username)->first();

        if (!$user) {
            return  response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([], 200);
    }
}

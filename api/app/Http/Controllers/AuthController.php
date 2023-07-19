<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\NewUserRequest;

class AuthController extends Controller
{
    function storeToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'error' => 'The email and password provided don\'t match out records'
            ], 422);
        }

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken('spa')->plainTextToken,
            'token_type' => 'Bearer'
        ]);
    }

    public function store(NewUserRequest $request)
    {
        try {
            $user = User::create($request->validated());

            $token = $user->createToken('spa')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (\Throwable $th) {

            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'error' => 'The email and password provided don\'t match out records'
            ], 422);
        }

        return response()->json([
            'user' => Auth::user(),
            'token' => Auth::user()->createToken('spa')->plainTextToken,

        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}

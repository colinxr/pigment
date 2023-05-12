<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\NewUserRequest;

class UserController extends Controller
{
    public function store(NewUserRequest $request)
    {
        try {
            $user = User::create($request->validated());

            $token = $user->createToken('auth_token')->plainTextToken;

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

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'nullable|unique:users',
            'password' => 'nullable',
            'password_confirmation' => 'required_with:password|same:password',
        ]);

        if ($request->username) {
            $user->username = $request->username;
        }

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'status' => '204',
            'message' => 'Update Successful',
            'data' => $user,
        ], 204);
    }
}

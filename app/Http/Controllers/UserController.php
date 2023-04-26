<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\NewUserRequest;

class UserController extends Controller
{
    public function store(NewUserRequest $request)
    {
        // $validated = $request->validate([
        //     'first_name' => 'required',
        //     'last_name' => 'required',
        //     'email' => 'required|unique:users|email',
        //     'username' => 'required|unique:users',
        //     'password' => 'required',
        //     'password_confirmation' => 'required|same:password',
        // ]);

        $user = User::create($request->all());

        return response()->json($user, 201);
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

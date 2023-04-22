<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

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
}

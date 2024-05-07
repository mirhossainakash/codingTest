<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // Create a new user with the provided name and account type
        $request->validate([
            'name' => 'required|string',
            'account_type' => 'required|in:Individual,Business'
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'account_type' => $request->input('account_type'),
            'balance' => 0, // Set initial balance to 0
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        return response()->json(['user' => $user, 'message' => 'User created successfully']);
    }
}
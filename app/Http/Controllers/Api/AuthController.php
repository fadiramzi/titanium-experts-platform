<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Password will be hashed automatically due to the 'hashed' cast in User model
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => $user,
        ]);
        
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user->password)) {
            // Generate a token for the user
            $token = $user->createToken('experts_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User logged in successfully',
                'user' => $user,
                'token' => $token,
            ]);
        }   
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);

    }
}

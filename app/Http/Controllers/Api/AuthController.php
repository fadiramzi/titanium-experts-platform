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
            'type' => 'required|string|in:customer,expert', // Ensure type is either customer or expert
        ]);
        $otpCode = rand(100000, 999999); // Generate a random OTP code
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Password will be hashed automatically due to the 'hashed' cast in User model
            'otp_code' => $otpCode, // Assuming you have an otp_code field in your users table
            'type' => $request->type, // Set the user type
        ]);

        // Send otp via SMS or email
        // Code TO DO sending otp to user
        // later

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully, please verify your account before login',
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

        if($user->email_verified_at === null) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your account before login',
            ], 403);
        }
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

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'otp_code' => 'required|string|min:6|max:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 401);
        }

        if ($user->otp_code !== $request->otp_code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP code',
            ], 400);
        }

        // Mark the user as verified
        $user->email_verified_at = now();
        $user->otp_code = null; // Clear the OTP code after verification
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Account verified successfully, you can now login'
        ]);
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete(); // Revoke all tokens for the user

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully',
        ]);
    }
}

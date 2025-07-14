<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpertController extends Controller
{
    //
    public function index()
    {
         return response()->json([
        "itemName"=>'ITem B',
        "itemPrice"=>250,
    ]);
    }

    public function signup(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:15|max:15',
        ]);

        // Store the expert's phone number into DB
        // Generate OTP and send it to the expert's phone number
        // Return success response

        return response()->json([
            'success' => true
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:15|max:15',
            'otp' => 'required|string|min:6|max:6',
        ]);

        // Verify the OTP for the expert's phone number
        // If successful, return success response
        // If failed, return error response

        return response()->json([
            'success' => true,
            'message' => 'Account verified successfully'
        ]);
    }
}

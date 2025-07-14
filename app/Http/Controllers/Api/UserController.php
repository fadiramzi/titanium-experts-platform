<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function getList()
    {
        return response()->json(
           [ // list
             [ // object
                'userId' => 1,
                'name' => 'John Doe',
                'email' => 'fadi@gmail.com'
             ],
             [ // object
                'userId' => 2,
                'name' => 'FAdi',
                'email' => 'raf@gmail.com'
            ]
           ]
        );
    }
    public function add(Request $request)
    {
        // check valdiation input
        $request->validate([
            'name'=>'required|string|max:10',
            'email'=>'required|email',
            'userId'=>'required|integer'
        ]);
        // if validation fails, it will return 422 error with validation errors

        // logic
        $name = strtoupper($request->input('name'));
        $email = $request->input('email');
        $userId = $request->input('userId');

        // fprmating response to the client
        // imagine creation user is done
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'userData' => [
                'userId' => $userId,
                'name' => $name,
                'email' => $email
            ]
        ]);
    }
}

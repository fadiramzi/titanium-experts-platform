<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function getList()
    {
        // connect to database and get the list of users
       $users =  User::all();
       // SELECT * FROM users;
       
        return response()->json(
          $users
        );
    }
    public function add(Request $request)
    {
        // check valdiation input
        $request->validate([
            'name'=>'required|string|max:10',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6'
        ]);
        // if validation fails, it will return 422 error with validation errors

        // logic
        $name = strtoupper($request->input('name'));
        $email = $request->input('email');
        $password = $request->input('password');

        // store data into DATABASE table users
        $user = User::create([
            'name'=>$name ,
            'email'=>$email,
            'password'=>$password // hashing password
        ]);

        // fprmating response to the client
        // imagine creation user is done
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'userData' => $user
        ]);
    }
}

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

    public function update(Request $request, $id)
    {
        //check validation input
        $request->validate([
            'name' => 'sometimes|string|max:10',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|min:6'
        ]);

        // logic
        $user = User::findOrFail($id);
        // $user-> name : FADI
        // $user-> email : fadi@gmail.com
        // 
        if($request->has('name'))
        {   
            $user->name = $request->input('name');
        }

        if($request->has('email'))
        {
            $user->email = $request->input('email');
        }
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'userData' => $user
        ]);
    }

    
}

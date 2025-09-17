<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function getList(Request $request)
    {
        $request->validate([
            'userType' => 'nullable|string|in:customer,expert', // Ensure userType is either customer or expert
            'industry' => 'nullable|string|max:255', // Optional industry filter
            'q' => 'nullable|string|max:255', // Optional search query
            'sortBy'=> 'nullable|string|in:name,session_price',
            'sortDir' => 'nullable|string|in:asc,desc',
            'page' => 'nullable|numeric|min:1',
            'perPage' => 'nullable|numeric|min:1|max:25'
        ]);
        $sortBy = $request->sortBy ?? 'id';
        $sortDir = $request->sortDir ?? 'desc';

        $page = $request->page ?? 1;
        $offset = ($page - 1) * ($request->perPage ?? 10); // Default to 10 per page
        $perPage = $request->perPage ?? 10;


         // extract the user type from the request URL params
         $userType = $request->userType ?? null; // Default to null if not provided
        // connect to database and get the list of users

        $query = User::query();
        $query = $query->with('expert'); // Eager load the expert relationship
        // SELECT * FROM users

        if($request->q) {
            // If search query is provided, filter users by name or email
            // SELECT * FROM users WHERE name LIKE %$request->q% OR email LIKE %$request->q%
            $searchTerm = '%' . $request->q . '%';
            $query = $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
            });
        }
        if($userType) {
            // If userType is provided, filter users by type
            // SELECT * FROM users WHERE type = $userType
            $query = $query->where('type', $userType);
        } 

        if($request->industry) {
            // If industry is provided, filter users by industry
            // SELECT * FROM users WHERE type = $userType && $userType = $request->userType
            $query = $query->whereHas('expert', function($q) use ($request) {
                $q->where('industry', $request->industry);
            });
        }
        // Handle sorting by related table columns
        if($sortBy === 'session_price') {
            // Join with experts table to sort by session_price
            $query = $query->join('experts', 'users.id', '=', 'experts.user_id') // Adjust join condition based on your FK
                        ->orderBy('experts.session_price', $sortDir)
                        ->select('users.*'); // Only select users columns to avoid duplicates
        } else {
            // Sort by users table columns
            $query = $query->orderBy($sortBy, $sortDir);
        }


        $totalCount = $query->count(); // Get total count for pagination
        $currentPage = $page;
        $offset = $offset; // Calculate offset for pagination

        $query = $query->offset($offset)
                          ->limit($perPage); // Apply pagination    

        $users = $query->get(); // result maybe 10
        
        // Paginate the results
        

        return response()->json([
            'status' => 'success',
            'message' => 'User list retrieved successfully',
            'data' => $users,
            'pagination' => [
                'total' => $totalCount,
                'current_page' =>  intval($currentPage),
                'per_page' => intval($perPage),
                'last_page' =>  intval(ceil($totalCount / $perPage)),
                'offset' =>  intval($offset)
            ]
        ]);
        
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

    public function delete(Request $request, $id)
    {
        // logic
        $request->validate([
            'id' => 'required|exists:users,id'
        ]);
        
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ]);
    }   

    // Retrurn my profile onlyt
    public function me(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'status' => 'success',
            'message' => 'User profile retrieved successfully',
            'userData' => $user
        ]);
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth; 

class UserController extends Controller
{
    function usersList()
    {
        $users = User::whereNull('parent_id')->with('childrenRecursive')->get();
        return response()->json($users);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){  
            $token =  Auth::user()->createToken('access_token')->plainTextToken;
            return response()->json(['message' => 'User login successfully', 'token' => $token]);
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        }
    }


    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'parent_id' => 'nullable|exists:users,id', // Ensure parent_id exists in the users table
        ]);
        
        $user = User::create($request->all());
        
        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json(['message' => 'User registered successfully', 'token' => $token]);
    }    
}

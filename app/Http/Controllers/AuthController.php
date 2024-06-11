<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create($validatedData);
        $token = $user->createToken('mytoken')->plainTextToken;

        return response()->json([
            'message' => 'register successfully',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function store(Request $request)
    {
        // Initialize the $user variable as a new User instance
        $user = new User();
        
        // Assign values to the user's properties
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        
        // Save the user to the database
        $user->save();

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }
}

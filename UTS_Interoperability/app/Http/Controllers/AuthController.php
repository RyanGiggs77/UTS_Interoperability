<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        // Validate the incoming request data
        $this->validate($request, [
            'nama'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        // Get all input data
        $input = $request->all();

        // Validation rules
        $validationRules = [
            'nama'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ];

        // Create a validator instance
        $validator = \Validator::make($input, $validationRules);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON response
            return response()->json($validator->errors(), 400);
        }

        // Create a new user
        $user = new User;
        $user->nama = $request->input('nama');
        $user->email = $request->input('email');

        // Hash the plain password and save it to the user
        $plainPassword = $request->input('password');
        $user->password = app('hash')->make($plainPassword);

        // Save the user to the database
        $user->save();

        // Return the user as JSON response
        return response()->json($user, 200);
    }

    
    public function login(Request $request)
    {
        $input = $request->all();

        // Validation
        $validationRules = [
            'email' => 'required|string',
            'password' => 'required|string',
        ];

        $validator = \Validator::make($input, $validationRules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Process login
        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ], 200);
    }
}

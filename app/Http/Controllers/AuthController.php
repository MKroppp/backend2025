<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validateRegisterData($request);

        $role = $request->input('role', 'client');

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        return response()->json(['message' => 'User created successfully'], 201);
    }

    public function login(Request $request)
    {

        $this->validateLoginData($request);

        if (!$token = JWTAuth::attempt($request->only(['email', 'password']))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(compact('token'));
    }

    private function validateRegisterData(Request $request)
    {
        if (empty($request->email) || empty($request->password)) {
            return response()->json(['error' => 'Email and Password are required'], 400);
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error' => 'Invalid email format'], 400);
        }

        if (!empty($request->role) && !in_array($request->role, ['admin', 'client'])) {
            return response()->json(['error' => 'Invalid role'], 400);
        }
    }

    private function validateLoginData(Request $request)
    {
        if (empty($request->email) || empty($request->password)) {
            return response()->json(['error' => 'Email and Password are required'], 400);
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error' => 'Invalid email format'], 400);
        }
    }
}

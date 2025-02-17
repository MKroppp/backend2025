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
        // Валидация через ручную проверку
        $this->validateRegisterData($request);

        // Определение роли (по умолчанию 'client')
        $role = $request->input('role', 'client');

        // Создание нового пользователя
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        return response()->json(['message' => 'User created successfully'], 201);
    }

    public function login(Request $request)
    {
        // Валидация данных входа
        $this->validateLoginData($request);

        // Попытка создать токен
        if (!$token = JWTAuth::attempt($request->only(['email', 'password']))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(compact('token'));
    }

    // Метод для валидации данных при регистрации
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

    // Метод для валидации данных при входе
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

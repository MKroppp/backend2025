<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleController extends Controller
{
    // Изменение роли пользователя
    public function changeRole(Request $request, $id)
    {
        // Проверка, что пользователь является администратором
        $user = JWTAuth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $userToUpdate = User::find($id);
        if (!$userToUpdate) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $role = $request->input('role');
        if (!in_array($role, ['admin', 'client'])) {
            return response()->json(['error' => 'Invalid role'], 400);
        }

        $userToUpdate->role = $role;
        $userToUpdate->save();

        return response()->json(['message' => 'User role updated successfully']);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string'],
            'age' => ['nullable', 'integer', 'min:5', 'max:100'],
            'education_stage' => ['nullable', 'in:Primary,Preparatory,Secondary'],
            'role' => ['nullable', 'in:user,parent,admin'],
        ]);

        $role = $data['role'] ?? 'user';
        // For regular student users, require age and education_stage
        if ($role === 'user') {
            $request->validate([
                'age' => ['required', 'integer', 'min:5', 'max:100'],
                'education_stage' => ['required', 'in:Primary,Preparatory,Secondary'],
            ]);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'age' => $data['age'] ?? null,
            'education_stage' => $data['education_stage'] ?? null,
            'role' => $role,
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
}

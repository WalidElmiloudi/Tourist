<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{

    #[OA\Post(path: '/api/register', summary: 'Register a new user')]
    #[OA\Parameter(name: 'name', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'email', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'password', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 201, description: 'User registered successfully')]

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'user regestired succefully',
            'user' => $user,
            'token' => $token
        ]);
    }

    #[OA\Post(path: '/api/login', summary: 'Log a user in')]
    #[OA\Parameter(name: 'email', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'password', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 201, description: 'User logged in successfully')]


    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'user logged in succefully',
            'user' => $user,
            'token' => $token
        ]);
    }

    #[OA\Post(
        path: '/api/logout',
        summary: 'Logging out a user',
        security: [['sanctum' => []]]
    )]
    #[OA\Response(response: 200, description: 'User logged out successfully')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully'], 200);
        }

        return response()->json(['message' => 'Not authenticated'], 401);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    // REGISTER
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);

        }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        // Set cookie with proper settings for localhost
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ])->cookie(
            'access_token',
            $token,
            60 * 24 * 15,              // 15 days in minutes
            '/',
            'localhost',               // Explicitly set domain
            false,                     // secure = false for http
            true,                      // httpOnly = true
            false,                     // raw
            'Lax'                      // sameSite
        );
    }

    // LOGOUT
    public function logout(Request $request): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'User successfully logged out'
        ]);
    }

    // GET AUTHENTICATED USER
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    public function refresh(): JsonResponse
    {
        $newToken = auth('api')->refresh();

        return response()->json([
            'access_token' => $newToken,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}

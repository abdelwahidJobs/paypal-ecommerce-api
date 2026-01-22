<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class JwtFromCookie
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('access_token');

        logger('JWT Cookie Middleware', [
            'has_cookie' => !is_null($token),
            'token' => $token ? substr($token, 0, 20) . '...' : 'null',
            'all_cookies' => $request->cookies->all()
        ]);

        if ($token) {
            try {
                // Set the token for JWT Auth
                $request->headers->set('Authorization', 'Bearer ' . $token);

                // Verify and authenticate
                $user = JWTAuth::setToken($token)->authenticate();

                logger('JWT authenticated user', ['user_id' => $user->id]);

            } catch (JWTException $e) {
                logger('JWT Exception', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Token invalid or expired'], 401);
            }
        } else {
            logger('No JWT cookie found');
            return response()->json(['error' => 'No token provided'], 401);
        }

        return $next($request);
    }
}
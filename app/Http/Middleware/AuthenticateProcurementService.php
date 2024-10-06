<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\JsonResponse;

class AuthenticateProcurementService
{
    public function handle($request, Closure $next)
    {
        try {
            if (! $token = $request->bearerToken()) {
                return new JsonResponse(['error' => 'Token not provided'], 401);
            }

            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return new JsonResponse(['error' => 'User not authenticated'], 401);
            }
        } catch (JWTException $e) {
            return new JsonResponse(['error' => 'Token invalid or expired'], 401);
        }

        return $next($request);
    }
}


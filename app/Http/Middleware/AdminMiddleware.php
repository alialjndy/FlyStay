<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            if($user && $user->hasRole('admin')){
                return $next($request);
            }

            // User is authenticated but not an admin
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Admins only.',
            ], Response::HTTP_FORBIDDEN);
        }catch(Exception){

            // Token is invalid, expired, or missing
            return response()->json([
                'message' => 'Unauthorized access. Please log in again.',
                'status' => 'error',
            ], Response::HTTP_UNAUTHORIZED);
        }

    }
}

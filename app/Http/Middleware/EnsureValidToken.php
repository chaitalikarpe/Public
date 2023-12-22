<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureValidToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            Auth::guard('sanctum')->authenticate();
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Invalid or missing Bearer token'], 401);
        }
        return $next($request);
    }
}

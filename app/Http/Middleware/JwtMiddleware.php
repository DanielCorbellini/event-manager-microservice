<?php

namespace App\Http\Middleware;

use Closure;
use Throwable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $gatewayKey = $request->header('X-Gateway-Key');
        $expectedKey = env('GATEWAY_SECRET');

        if (empty($gatewayKey) || $gatewayKey !== $expectedKey) {
            return $this->unauthorized("Acesso negado");
        }

        return $next($request);
    }

    private function unauthorized(string $message, ?string $details = null)
    {
        return response()->json([
            'error' => $message,
            'details' => $details,
        ], 403);
    }
}

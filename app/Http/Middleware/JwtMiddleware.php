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
        $token = $request->bearerToken();

        if (empty($token)) {
            return $this->unauthorized("Token não fornecido");
        }

        try {
            // Decodificar o token e deixar rolar se não der problema
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            $request->attributes->set('jwt', $decoded);
        } catch (Throwable $e) {
            return $this->unauthorized("Token inválido ou expirado", $e->getMessage());
        }

        return $next($request);
    }

    private function unauthorized(string $message, ?string $details = null)
    {
        return response()->json([
            'error' => $message,
            'details' => $details,
        ], 401);
    }
}

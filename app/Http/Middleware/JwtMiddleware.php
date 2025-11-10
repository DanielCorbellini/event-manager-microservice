<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // try {
        //     // Tenta autenticar via token
        //     $user = JWTAuth::parseToken()->authenticate();
        //     if (!$user) {
        //         return new JsonResponse(['error' => 'Usuário não encontrado'], 401);
        //     }

        //     // Define o usuário como autenticado
        // Usar a classe Auth::user() depois nas controllers / service
        //     auth()->setUser($user);
        // } catch (JWTException $e) {
        //     return new JsonResponse(['error' => 'Token inválido ou ausente'], 401);
        // }

        return $next($request);
    }
}

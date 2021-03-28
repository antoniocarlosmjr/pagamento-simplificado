<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\{TokenExpiredException, TokenInvalidException};
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

/**
 * Class ApiProtectRoute
 * @package App\Http\Middleware
 * @author Antonio Martins
 */
class ApiProtectRoute extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(['error' => 'Token inválido']);
            } elseif ($e instanceof TokenExpiredException) {
                return response()->json(['error' => 'Token expirado']);
            } else {
                return response()->json(['error' => 'Token de autorização não encontrado']);
            }
        }
        return $next($request);
    }
}

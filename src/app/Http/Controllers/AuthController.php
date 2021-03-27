<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Validator;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthController
 * @package App\Http\Controllers
 * @author Antonio Martins
 */
class AuthController extends Controller
{
    /**
     * Realiza o login do usuário para retornar os dados para acesso
     * as credenciais do JWT.
     *
     * @param Request $request
     * @return JsonResponse
     * @author Antonio Martins
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!$token = auth('api')->attempt($validator->validated())) {
            return response()->json(
                ['error' => 'Não Autorizado'],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        return $this->criarRetornoToken($token);
    }

    /**
     * Retorna a estrutura com os dados do token.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function criarRetornoToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}

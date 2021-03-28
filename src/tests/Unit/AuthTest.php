<?php

namespace Tests\Unit;

use App\Models\Carteira;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * Class AuthTest
 * @package Tests\Unit
 * @author Antonio Martins
 */
class AuthTest extends TestCase
{
    /**
     * Retorna um array com o usuário cadastrado para fins de teste
     * de autenticação.
     *
     * @return array
     * @author Antonio Martins
     */
    public function cadastrarUsuario(): array
    {
        $usuarioCadastrado = User::factory()->create();
        $usuarioCadastrado->each(
            fn (User $user) => Carteira::factory(
                [
                    'id_usuario' => $user->id,
                    'saldo' => 100
                ]
            )->create()
        );
        return [
            'email' => $usuarioCadastrado->email,
            'password' => '12345678'
        ];
    }

    /**
     * Testa a autenticação do usuário.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testLogin()
    {
        $usuarioCadastrado = $this->cadastrarUsuario();
        $response = $this->post('api/login', $usuarioCadastrado);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
        $response->assertJsonStructure(
            [
                'access_token',
                'token_type',
                'expires_in'
            ],
            $response->json()
        );
    }

    /**
     * Testa uma autenticação com dados incorretos.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testLoginDadosInvalidos()
    {
        $usuarioCadastrado = $this->cadastrarUsuario();
        $usuarioCadastrado['password'] = 'senha123';

        $response = $this->post('api/login', $usuarioCadastrado);
        $this->assertEquals(JsonResponse::HTTP_UNAUTHORIZED, $response->status());
        $this->assertEquals($response->json(),
            [
                'error' => 'Não Autorizado',
            ]
        );
    }
}

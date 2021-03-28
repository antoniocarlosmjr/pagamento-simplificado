<?php

namespace Tests\Unit;

use App\Models\Carteira;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * Class CarteiraTest
 * @package Tests\Unit
 * @author Antonio Martins
 */
class CarteiraTest extends TestCase
{
    use DatabaseTransactions;

    const SALDO_INICIAL_USUARIO = 50.00;

    /**
     * Retorna o token de um determinado usuário que acaba de ser cadastrado.
     *
     * @return string
     * @author Antonio Martins
     */
    public function retornarTokenUsuario(): string
    {
        $usuarioCadastrado = User::factory()->create();
        $usuarioCadastrado->each(
            fn (User $user) => Carteira::factory(
                [
                    'id_usuario' => $user->id,
                    'saldo' => self::SALDO_INICIAL_USUARIO
                ]
            )->create()
        );

        $dados = [
            'email' => $usuarioCadastrado->email,
            'password' => '12345678'
        ];

        $response = $this->post('api/login', $dados);
        return $response->json('access_token');
    }

    /**
     * Testa o retorno de uma determinada carteira do usuário com saldo
     * definido na constante como saldo inicial.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testRetornoCarteira()
    {
        $response = $this->json(
            'GET',
            'api/carteira', [],
            ['Authorization' => "Bearer {$this->retornarTokenUsuario()}"]);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
        $this->assertEquals(self::SALDO_INICIAL_USUARIO, $response->json('saldo'));
        $response->assertJsonStructure(
            [
                'id',
                'saldo',
                'id_usuario',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $response->json()
        );
    }

    /**
     * Testa consultar a carteira do usuário logado pelo token.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testTentarRetornarCarteiraSemToken()
    {
        $response = $this->get('api/carteira');
        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
        $this->assertEquals($response->json(),
            [
                'error' => 'Token de autorização não encontrado'
            ]
        );
    }

    /**
     * Testa a consulta da carteira do usuário com token informado inválido.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testTentarRetornarCarteiraTokenInvalido()
    {
        $response = $this->json('GET', 'api/carteira', [], ['Authorization' => 'Bearer 123']);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
        $this->assertEquals($response->json(),
            [
                "error" => "Token inválido"
            ]
        );
    }
}

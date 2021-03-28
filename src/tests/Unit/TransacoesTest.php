<?php

namespace Tests\Unit;

use App\Constantes\TiposUsuariosConstante;
use App\Models\Carteira;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * Class TransacoesTest
 * @package Tests\Unit
 * @author Antonio Martins
 */
class TransacoesTest extends TestCase
{
    use DatabaseTransactions;

    const SALDO_INICIAL_VAZIO = 0;
    const SALDO_INICIAL_CEM = 100;

    /**
     * Retorna um array com dados do usuário que acaba de ser cadastrado
     * e o seu token de autenticação.
     *
     * @param string $saldoInicial
     * @param string $tipoUsuario
     * @return array
     * @author Antonio Martins
     */
    public function retornarUsuarioCadastrado(string $saldoInicial, string $tipoUsuario): array
    {
        $usuarioCadastrado = User::factory()->create(['tipo' => $tipoUsuario]);
        $usuarioCadastrado->each(
            fn (User $user) => Carteira::factory(
                [
                    'id_usuario' => $user->id,
                    'saldo' => $saldoInicial
                ]
            )->create()
        );

        $dados = [
            'email' => $usuarioCadastrado->email,
            'password' => '12345678'
        ];

        $response = $this->post('api/login', $dados);
        return [
            'usuario' => $usuarioCadastrado,
            'token' => $response->json('access_token')
        ];
    }

    /**
     * Testa uma transferência entre dois usuários comuns, onde o primeiro usuário tem saldo de 100 e
     * o segundo possui saldo zerado.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testRealizarTransferenciaDeUsuarioComum()
    {
        $usuarioPagador = $this->retornarUsuarioCadastrado(
            self::SALDO_INICIAL_CEM,
            TiposUsuariosConstante::USUARIO_COMUM
        );

        $usuarioBeneficiario = $this->retornarUsuarioCadastrado(
            self::SALDO_INICIAL_VAZIO,
            TiposUsuariosConstante::USUARIO_COMUM
        );

        $response = $this->json(
            'POST',
            'api/transacao', [
                'id_usuario_pagador' => $usuarioPagador['usuario']->id,
                'id_usuario_beneficiario' => $usuarioBeneficiario['usuario']->id,
                'valor' => self::SALDO_INICIAL_CEM
            ],
            ['Authorization' => "Bearer {$usuarioPagador['token']}"]);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->status());
        $this->assertEquals($response->json(),
            [
                'success' => "Transferência realizada com sucesso!",
            ]
        );
        $this->assertDatabaseHas('carteiras', [
            'id_usuario' => $usuarioBeneficiario['usuario']->id,
            'saldo' => self::SALDO_INICIAL_CEM
        ]);
    }

    /**
     * Testa realizar a transferência de um usuário lojista.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testRealizarTransferenciaDeLojista()
    {
        $usuarioPagador = $this->retornarUsuarioCadastrado(
            self::SALDO_INICIAL_CEM,
            TiposUsuariosConstante::USUARIO_LOJISTA
        );

        $usuarioBeneficiario = $this->retornarUsuarioCadastrado(
            self::SALDO_INICIAL_VAZIO,
            TiposUsuariosConstante::USUARIO_COMUM
        );

        $response = $this->json(
            'POST',
            'api/transacao', [
            'id_usuario_pagador' => $usuarioPagador['usuario']->id,
            'id_usuario_beneficiario' => $usuarioBeneficiario['usuario']->id,
            'valor' => self::SALDO_INICIAL_CEM
        ],
            ['Authorization' => "Bearer {$usuarioPagador['token']}"]);

        $this->assertEquals(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($response->json(),
            [
                'message' => "Erro ao tentar realizar transferência",
                'error' => "Lojistas não podem realizar transferência.",
            ]
        );
    }

    /**
     * Testa realizar uma determinada transferência sem saldo na carteira.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testRealizarTransferenciaUsuarioSemSaldoCarteira()
    {
        $usuarioPagador = $this->retornarUsuarioCadastrado(
            self::SALDO_INICIAL_VAZIO,
            TiposUsuariosConstante::USUARIO_COMUM
        );

        $usuarioBeneficiario = $this->retornarUsuarioCadastrado(
            self::SALDO_INICIAL_VAZIO,
            TiposUsuariosConstante::USUARIO_LOJISTA
        );

        $response = $this->json(
            'POST',
            'api/transacao', [
            'id_usuario_pagador' => $usuarioPagador['usuario']->id,
            'id_usuario_beneficiario' => $usuarioBeneficiario['usuario']->id,
            'valor' => self::SALDO_INICIAL_CEM
        ],
            ['Authorization' => "Bearer {$usuarioPagador['token']}"]);

        $this->assertEquals(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($response->json(),
            [
                'message' => "Erro ao tentar realizar transferência",
                'error' => "Saldo na carteira do usuário pagador é insuficiente.",
            ]
        );
    }

    /**
     * Testa realizar uma transferência para ele mesmo.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testRealizarTransferenciaParaProprioClientePagador()
    {
        $usuarioPagador = $this->retornarUsuarioCadastrado(
            self::SALDO_INICIAL_CEM,
            TiposUsuariosConstante::USUARIO_COMUM
        );

        $response = $this->json(
            'POST',
            'api/transacao', [
            'id_usuario_pagador' => $usuarioPagador['usuario']->id,
            'id_usuario_beneficiario' => $usuarioPagador['usuario']->id,
            'valor' => self::SALDO_INICIAL_CEM
        ],
            ['Authorization' => "Bearer {$usuarioPagador['token']}"]);

        $this->assertEquals(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($response->json(),
            [
                'message' => "Erro ao tentar realizar transferência",
                'error' => "Usuário não pode transferir para ele mesmo.",
            ]
        );
    }

    /**
     * Testa realizar uma transferência para de usuário pagador que não existe.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testRealizarTransferenciaUsuarioPagadorInexistente()
    {
        $usuarioPagador = $this->retornarUsuarioCadastrado(
            self::SALDO_INICIAL_CEM,
            TiposUsuariosConstante::USUARIO_COMUM
        );

        $usuarioBeneficiario = $this->retornarUsuarioCadastrado(
            self::SALDO_INICIAL_VAZIO,
            TiposUsuariosConstante::USUARIO_LOJISTA
        );

        $response = $this->json(
            'POST',
            'api/transacao', [
            'id_usuario_pagador' => "9999999999",
            'id_usuario_beneficiario' => $usuarioBeneficiario['usuario']->id,
            'valor' => self::SALDO_INICIAL_CEM
        ],
            ['Authorization' => "Bearer {$usuarioPagador['token']}"]);

        $this->assertEquals(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($response->json(),
            [
                'message' => "Erro ao tentar realizar transferência",
                'errors' => [
                    "id_usuario_pagador" =>[
                        "Usuário pagador é inexistente na base de dados."
                    ]
                ]
            ]
        );
    }

    /**
     * Testa realizar transferencia para usuário beneficiário que não existe.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testRealizarTransferenciaUsuarioBeneficiarioInexistente()
    {
        $usuarioPagador = $this->retornarUsuarioCadastrado(
            self::SALDO_INICIAL_CEM,
            TiposUsuariosConstante::USUARIO_COMUM
        );

        $response = $this->json(
            'POST',
            'api/transacao', [
            'id_usuario_pagador' => $usuarioPagador['usuario']->id,
            'id_usuario_beneficiario' => "999999999",
            'valor' => self::SALDO_INICIAL_CEM
        ],
            ['Authorization' => "Bearer {$usuarioPagador['token']}"]);

        $this->assertEquals(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($response->json(),
            [
                'message' => "Erro ao tentar realizar transferência",
                'errors' => [
                    "id_usuario_beneficiario" =>[
                        "Usuário beneficiário inexistente na base de dados."
                    ]
                ]
            ]
        );
    }

    /**
     * Testa realizar transferência sem dados informados.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testRealizarTransferenciaCamposIncompletos()
    {
        $usuarioPagador = $this->retornarUsuarioCadastrado(
            self::SALDO_INICIAL_CEM,
            TiposUsuariosConstante::USUARIO_COMUM
        );

        $response = $this->json(
            'POST',
            'api/transacao', [],
            ['Authorization' => "Bearer {$usuarioPagador['token']}"]);

        $this->assertEquals(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $response->status());
    }

    /**
     * Testa o retorna as transações de um determinado usuário.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testRetornarTransacoes()
    {
        $usuarioLogado = $this->retornarUsuarioCadastrado(
            self::SALDO_INICIAL_CEM,
            TiposUsuariosConstante::USUARIO_COMUM
        );

        $response = $this->json(
            'GET',
            'api/transacao', [],
            ['Authorization' => "Bearer {$usuarioLogado['token']}"]);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
    }

    /**
     * Testa consultar a carteira do usuário logado pelo token.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testRealizarTransferenciaSemToken()
    {
        $response = $this->post('api/transacao');
        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
        $this->assertEquals($response->json(),
            [
                'error' => 'Token de autorização não encontrado'
            ]
        );
    }

    /**
     * Testa a realização de uma transferência com a autenticação
     * do token informado inválido.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testRealizarTransferenciaTokenInvalido()
    {
        $response = $this->json('POST', 'api/transacao', [], ['Authorization' => 'Bearer 123']);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
        $this->assertEquals($response->json(),
            [
                "error" => "Token inválido"
            ]
        );
    }
}

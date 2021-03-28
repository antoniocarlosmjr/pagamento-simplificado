<?php

namespace Tests\Unit;

use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * Class TransacoesTest
 * @package Tests\Unit
 * @author Antonio Martins
 */
class TransacoesTest extends TestCase
{
    public function testRealizarTransferenciaUsuarioComum()
    {

    }

    public function testRealizarTransferenciaLojista()
    {

    }

    public function testRealizarTransacaoUsuarioSemSaldoCarteira()
    {

    }

    public function testRealizarTransacaoParaProprioClientePagador()
    {

    }

    public function testRealizarTransferenciaUsuarioPagadorInexistente()
    {

    }

    public function testRealizarTransferenciaUsuarioBeneficiarioInexistente()
    {

    }

    public function testRealizarTransferenciaCamposIncompletos()
    {

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

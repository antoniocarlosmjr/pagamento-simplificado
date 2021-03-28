<?php

namespace Tests\Unit;

use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * Class CarteiraTest
 * @package Tests\Unit
 * @author Antonio Martins
 */
class CarteiraTest extends TestCase
{
    /**
     * Testa o retorno de uma determinada carteira do usuário.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testRetornoCarteira()
    {

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

<?php

namespace Tests\Unit;

use App\Models\Carteira;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * Class UserTest
 * @package Tests\Unit
 * @author Antonio Martins
 */
class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Retorna um array com um usuário gerado através da fábrica.
     *
     * @return array
     * @author Antonio Martins
     *
     */
    public function geradorUsuarioGenerico(): array
    {
        $usuario = User::factory()->make();
        return [
            "nome" => $usuario->nome,
            "email" => $usuario->email,
            "cpf_cnpj" => $usuario->cpf_cnpj,
            "tipo" => $usuario->tipo,
            "password" => '12345678'
        ];
    }

    /**
     * Retorna uma collection com o usuário cadastrado para fins de teste.
     *
     * @return Collection|Model|mixed
     * @author Antonio Martins
     *
     */
    public function cadastrarUsuario()
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
        return $usuarioCadastrado;
    }

    /**
     * Testa a criação de um novo usuário.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testCriacaoNovoUsuario(): void
    {
        $usuario = $this->geradorUsuarioGenerico();
        $response = $this->post('api/usuario', $usuario);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->status());
    }

    /**
     * Testa a criação de um novo usuário com documento já existente,
     * pois o cadastro do cliente deve ser único.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testCriarUsuarioDocumentoExistente(): void
    {
        $usuarioCadastrado = $this->cadastrarUsuario();
        $novoUsuario = $this->geradorUsuarioGenerico();
        $novoUsuario['cpf_cnpj'] = $usuarioCadastrado->cpf_cnpj;

        $response = $this->post('api/usuario', $novoUsuario);
        $this->assertEquals(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($response->json(),
            [
                'message' => 'Erro ao cadastrar usuário',
                'errors' => [
                    'cpf_cnpj' => [
                        'Já existe um usuário com este CPF/CNPJ.'
                    ]
                ]
            ]
        );
    }

    /**
     * Testa a criação de um novo usuário com um email de um outro usuário
     * já existente na base.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testCriarUsuarioEmailExistente(): void
    {
        $usuarioCadastrado = $this->cadastrarUsuario();
        $novoUsuario = $this->geradorUsuarioGenerico();
        $novoUsuario['email'] = $usuarioCadastrado->email;

        $response = $this->post('api/usuario', $novoUsuario);
        $this->assertEquals(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($response->json(),
            [
                'message' => 'Erro ao cadastrar usuário',
                'errors' => [
                    'email' => [
                        'Já existe um usuário com este email.'
                    ]
                ]
            ]
        );
    }

    /**
     * Testa a criação de um usuário com documento inválido.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testCriarUsuarioDocumentoInvalido(): void
    {
        $novoUsuario = $this->geradorUsuarioGenerico();
        $novoUsuario['cpf_cnpj'] = '00000000000000';

        $response = $this->post('api/usuario', $novoUsuario);
        $this->assertEquals(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($response->json(),
            [
                'message' => 'Erro ao cadastrar usuário',
                'errors' => [
                    'cpf_cnpj' => [
                        'O campo cpf cnpj não é um CPF ou CNPJ válido.'
                    ]
                ]
            ]
        );
    }

    /**
     * Testa a criação de um novo usuário com dados incompletos na requisição.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testCriarUsuarioAlgumCampoObrigatorioVazio(): void
    {
        $response = $this->post('api/usuario', []);
        $this->assertEquals(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $response->status());
    }

    /**
     * Testa a criação de um novo usuário com tipo informado diferente do lojista
     * ou comum.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function testCriarUsuarioTipoInvalido()
    {
        $novoUsuario = $this->geradorUsuarioGenerico();
        $novoUsuario['tipo'] = 'Tipo inexistente';

        $response = $this->post('api/usuario', $novoUsuario);
        $this->assertEquals(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($response->json(),
            [
                'message' => 'Erro ao cadastrar usuário',
                'errors' => [
                    'tipo' => [
                        "Tipo de usuário deve ser 'comum' ou 'lojista'."
                    ]
                ]
            ]
        );
    }
}

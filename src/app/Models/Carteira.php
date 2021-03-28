<?php

namespace App\Models;

use App\Exceptions\CarteiraException;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

/**
 * Class Carteira
 * @package App\Models
 * @author Antonio Martins
 */
class Carteira extends Model
{
    use HasFactory;

    const VALOR_INICIAL_CARTEIRA = 0;

    protected $table = "carteiras";
    protected $fillable = [
        'id_usuario',
        'saldo'
    ];

    /**
     * Retorna um array com regras para a carteira do usuário.
     *
     * @return array
     * @author Antonio Martins
     */
    public function regras(): array
    {
        return [
            'id_usuario' => [
                'required',
                'numeric',
                Rule::exists('users', 'id')
            ],
            'saldo' => 'required|numeric|min:0|not_in:0'
        ];
    }

    /**
     * Retorna um array com as mensagens padrões da transação.
     *
     * @return array
     * @author Antonio Martins
     */
    public function mensagens(): array
    {
        return [
            'id_usuario.required' => 'Id do usuário pagador é obrigatório.',
            'id_usuario.exists' => 'Id do usuário é inexistente na base de dados.',
            'id_usuario.numeric' => 'Id do usuário deve ser um número.',
            'saldo.required' => "O saldo é obrigatório.",
            'saldo.not_in' => 'O saldo deve ser maior que zero.',
        ];
    }

    /**
     * Realiza a criação de uma carteira de um usuário.
     *
     * @param User $usuarioModel
     * @return Carteira
     * @throws CarteiraException
     * @author Antonio Martins
     */
    public function criarCarteiraUsuario(User $usuarioModel): Carteira
    {
        try {
            $dados['id_usuario'] =  $usuarioModel->id;
            $dados['saldo'] =  self::VALOR_INICIAL_CARTEIRA;

            return $this->create($dados);
        } catch (Exception $e) {
            throw new CarteiraException(
                'Erro ao tentar cadastrar a carteira',
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * Adiciona um determinado valor na carteira do usuário.
     *
     * @param float $valor
     * @return void
     * @author Antonio Martins
     */
    public function adicionarValor(float $valor): void
    {
        $this->setAttribute('saldo', $this->getAttribute('saldo') + $valor);
    }

    /**
     * Diminui um determinado valor na carteira do usuário.
     *
     * @param float $valor
     * @return void
     * @author Antonio Martins
     */
    public function diminuirValor(float $valor): void
    {
        $this->setAttribute('saldo', $this->getAttribute('saldo') - $valor);
    }

    /**
     * Retorna se uma determinada carteira possui pelo menos um valor maior ou igual a um
     * valor definido no parâmetro.
     *
     * @param float $valor
     * @return bool
     * @author Antonio Martins
     */
    public function carteiraPossuiValorSuficiente(float $valor): bool
    {
        return $this->getAttribute('saldo') >= $valor;
    }
}

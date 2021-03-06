<?php

namespace App\Models;

use App\Constantes\SituacoesTransacaoConstante;
use App\Exceptions\TransacaoException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use TransferenciaRecebida;

/**
 * Class Transacao
 * @package App\Models
 * @author Antonio Martins
 */
class Transacao extends Model
{
    use HasFactory;
    const URL_AUTORIZACAO_TRANSACAO = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';

    protected $table = "transacoes";
    protected $fillable = [
        'id_usuario_pagador',
        'id_usuario_beneficiario',
        'valor'
    ];

    /**
     * Retorna um array de regras da transação.
     *
     * @return array
     * @author Antonio Martins
     *
     */
    public function regras(): array
    {
        return [
            'id_usuario_pagador' => [
                'required',
                'numeric',
                Rule::exists('users', 'id')
            ],
            'id_usuario_beneficiario' => [
                'required',
                'numeric',
                Rule::exists('users', 'id')
            ],
            'valor' => 'required|numeric|min:0|not_in:0'
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
            'id_usuario_pagador.required' => 'Id do usuário pagador é obrigatório.',
            'id_usuario_pagador.exists' => 'Usuário pagador é inexistente na base de dados.',
            'id_usuario_pagador.numeric' => 'Id do usuário pagador deve ser um número.',
            'id_usuario_beneficiario.required' => 'Id do usuário beneficiário é obrigatório.',
            'id_usuario_beneficiario.exists' => 'Usuário beneficiário inexistente na base de dados.',
            'id_usuario_beneficiario.numeric' => 'Id do usuário beneficiário deve ser um número.',
            'valor.required' => "O valor da transferência é obrigatório.",
            'valor.not_in' => 'O valor da transferência deve ser maior que zero.',
        ];
    }

    /**
     * Realiza a transferência entre dois usuários passados por parâmetro, sendo que o usuário pagador
     * é o usuário que está realizando a transferência para um usuário beneficiário. Além disso, também é passado
     * um determinado valor para a transação.
     *
     * @param User $usuarioPagador
     * @param User $usuarioBeneficiario
     * @param Transacao $transacaoModel
     * @return Transacao
     * @throws TransacaoException
     * @author Antonio Martins
     */
    public function transferencia(
        User $usuarioPagador,
        User $usuarioBeneficiario,
        Transacao $transacaoModel
    ): Transacao {
        if ($usuarioPagador->usuarioLojista()) {
            throw new TransacaoException("Lojistas não podem realizar transferência.");
        }

        if ($usuarioPagador->getAttribute('id') == $usuarioBeneficiario->getAttribute('id')) {
            throw new TransacaoException("Usuário não pode transferir para ele mesmo.");
        }

        $carteiraUsuarioPagadorObj = $usuarioPagador->getCarteira();
        $carteiraUsuarioBeneficiarioObj = $usuarioBeneficiario->getCarteira();

        if (!$carteiraUsuarioPagadorObj->carteiraPossuiValorSuficiente($transacaoModel->valor)) {
            throw new TransacaoException("Saldo na carteira do usuário pagador é insuficiente.");
        }

        if (!$this->autorizarTransacao()) {
            throw new TransacaoException("Transferência não autorizada.");
        }

        $carteiraUsuarioPagadorObj->diminuirValor($transacaoModel->valor);
        $carteiraUsuarioBeneficiarioObj->adicionarValor($transacaoModel->valor);

        $carteiraUsuarioPagadorObj->save();
        $carteiraUsuarioBeneficiarioObj->save();

        $transacaoModel->setAttribute('situacao', SituacoesTransacaoConstante::FINALIZADA);

        return $transacaoModel;
    }

    /**
     * Retorna um boolean que representa se a transação está autorizada
     * por um serviço externo.
     *
     * @return bool
     * @author Antonio Martins
     */
    public function autorizarTransacao(): bool
    {
        $resposta = Http::get(self::URL_AUTORIZACAO_TRANSACAO);
        return $resposta->ok();
    }
}

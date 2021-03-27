<?php

namespace App\Models;

use App\Constantes\TiposUsuariosConstante;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

/**
 * Class Transacao
 * @package App\Models
 * @author Antonio Martins
 */
class Transacao extends Model
{
    use HasFactory;

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
                Rule::exists('users', 'id')->whereNot('tipo', TiposUsuariosConstante::USUARIO_LOJISTA),
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
            'id_usuario_pagador.exists' => 'Usuário pagador não deve ser lojista ou é inexistente na base de dados.',
            'id_usuario_pagador.numeric' => 'Id do usuário pagador deve ser um número.',
            'id_usuario_beneficiario.required' => 'Id do usuário beneficiário é obrigatório.',
            'id_usuario_beneficiario.exists' => 'Id do usuário beneficiário inexistente na base de dados.',
            'id_usuario_beneficiario.numeric' => 'Id do usuário beneficiário deve ser um número.',
            'valor.required' => "O valor da transferência é obrigatório.",
            'valor.not_in' => 'O valor da transferência deve ser maior que zero.',
        ];
    }
}

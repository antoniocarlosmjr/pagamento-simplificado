<?php

namespace App\Models;

use App\Constantes\TiposUsuariosConstante;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;

/**
 * Class Usuario
 * @package App\Models
 * @author Antonio Martins
 */
class Usuario extends Model
{
    use Notifiable;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'email',
        'cpf_cnpj',
        'senha',
        'tipo'
    ];

    protected $hidden = ['senha'];

    /**
     * Retorna um array de regras dos campos utilizados para cadastrar um usuário.
     *
     * @return array
     * @author Antonio Martins
     *
     */
    public function regras(): array
    {
        return [
            'nome' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('usuarios','email')->ignore('id_usuario')
            ],
            'cpf_cnpj' => [
                'required',
                'cpf_ou_cnpj',
                'formato_cpf_ou_cnpj',
                Rule::unique('usuarios', 'cpf_cnpj')->ignore('id_usuario')
            ],
            'senha' => 'required|min:8|max:20',
            'tipo' => [
                'required',
                Rule::in(TiposUsuariosConstante::USUARIO_COMUM, TiposUsuariosConstante::USUARIO_LOJISTA)
            ],
        ];
    }

    /**
     * Retorna as possíveis mensagens dos tipos de erros de validação da requisição.
     *
     * @return string[]
     * @author Antonio Martins
     *
     */
    public function mensagens(): array
    {
        return [
            'nome.required'     => 'Nome do usuário é obrigatório.',
            'email.required'    => 'Email é obrigatório.',
            'tipo.required'     => 'Tipo do usuário é obrigatório.',
            'cpf_cnpj.required' => 'CPF/CNPJ deve é obrigatório.',
            'senha.required' => 'Senha é obrigatória.',
            'email.unique'      => 'Já existe um usuário com este email.',
            'cpf_cnpj.unique'   => 'Já existe um usuário com este CPF/CNPJ.',
            'email.email'       => 'Email informado não é válido.',
            'tipo.in'           => "Tipo de usuário deve ser 'comum' ou 'logista'.",
            'cpf_cnpj.in'       => 'CPF/CNPJ informado não é valido.',
            'senha.min'      => 'A senha deve ter no mínimo 8 e no máximo 20 caracteres.',
            'senha.max'      => 'A senha deve ter no mínimo 8 e no máximo 20 caracteres.',
        ];
    }
}
